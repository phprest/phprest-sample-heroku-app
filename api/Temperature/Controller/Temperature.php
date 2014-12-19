<?php namespace Api\Temperature\Controller;

use Phprest\Util\Controller;
use Symfony\Component\HttpFoundation\Request;
use Phprest\Response;
use Api\Temperature\Entity;
use Phprest\Exception;
use Phprest\Service;
use JMS\Serializer\Exception\RuntimeException;
use Doctrine\Common\Collections\Criteria;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Swagger\Annotations as Docs;
use Phprest\Annotation as Phprest;

/**
 * @Docs\Resource(
 *      apiVersion="0.1",
 *      swaggerVersion="0.9.6",
 *      resourcePath="/temperatures",
 *      basePath="/"
 * )
 */
class Temperature extends Controller
{
    use Service\Hateoas\Getter, Service\Orm\Getter, Service\RequestFilter\Getter;
    use Service\Hateoas\Util, Service\Validator\Util, Service\RequestFilter\Util;

    /**
     * @Phprest\Route(method="GET", path="/temperatures/{id:number}")
     *
     * @Docs\Api(
     *      path="/temperatures/{id}",
     *      @Docs\Operation(
     *          method="GET", summary="Gets a temperature",
     *          @Docs\Parameter(name="id", required=true, type="integer", paramType="path"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=404, message="Not Found"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @param Request $request
     * @param integer $id
     * @return Response\Ok
     * @throws Exception\NotFound
     */
    public function get(Request $request, $id)
    {
        $repo = $this->serviceOrm()->getRepository('Api\Temperature\Entity\Temperature');

        if (is_null($temperature = $repo->find($id))) {
            throw new Exception\NotFound();
        }

        return new Response\Ok($temperature);
    }

    /**
     * @Phprest\Route(method="GET", path="/temperatures")
     *
     * @Docs\Api(
     *      path="/temperatures",
     *      @Docs\Operation(
     *          method="GET", summary="Gets temperatures",
     *          @Docs\Parameter(name="page", type="integer", paramType="query", defaultValue=1),
     *          @Docs\Parameter(name="limit", required=false, type="integer", paramType="query", defaultValue=20),
     *          @Docs\Parameter(name="query", type="string", paramType="query", defaultValue="value>-10", description="field1<=100,field2=value"),
     *          @Docs\Parameter(name="sort", required=false, type="string", paramType="query", defaultValue="-created", description="field1,-field2"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=400, message="Not Found"),
     *          @Docs\ResponseMessage(code=400, message="Bad Request"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @param Request $request
     * @return Response\Ok
     * @throws Exception\NotFound
     * @throws Exception\BadRequest
     */
    public function getAll(Request $request)
    {
        $criteria = Criteria::create();
        $processor = new Service\RequestFilter\Processor\Orm($criteria, [
            'created' => function($value) {
                return new \DateTime($value);
            }
        ]);

        if (is_null($request->query->get('page'))) {
            $request->query->set('page', 1);
        }
        if (is_null($request->query->get('limit'))) {
            $request->query->set('limit', 20);
        }

        try {
            $this->serviceRequestFilter()->processQuery($request, $processor);
            $this->serviceRequestFilter()->processSort($request, $processor);
        } catch (\Exception $e) {
            throw new Exception\BadRequest(0, [$e->getMessage()]);
        }

        $repo = $this->serviceOrm()->getRepository('Api\Temperature\Entity\Temperature');

        try {
            $temperatures = $repo->matching($criteria);

            $criteria->setFirstResult(($request->query->get('page') - 1) * $request->query->get('limit'));
            $criteria->setMaxResults($request->query->get('limit'));

            $paginatedTemperatures = $repo->matching($criteria);
        } catch (\Exception $e) {
            throw new Exception\BadRequest(0, [$e->getMessage()]);
        }

        if ( ! count($paginatedTemperatures)) {
            throw new Exception\NotFound();
        }

        $temperatures = new PaginatedRepresentation(
            new CollectionRepresentation($paginatedTemperatures),
            '/temperatures',
            $request->query->all(),
            (int)$request->query->get('page'),
            (int)$request->query->get('limit'),
            ceil(count($temperatures) / $request->query->get('limit')),
            'page',
            'limit',
            true,
            count($temperatures)
        );

        return new Response\Ok($temperatures);
    }

    /**
     * @Phprest\Route(method="DELETE", path="/temperatures/{id:number}")
     *
     * @Docs\Api(
     *      path="/temperatures/{id}",
     *      @Docs\Operation(
     *          method="DELETE", summary="Deletes a temperature",
     *          @Docs\Parameter(name="id", required=true, type="integer", paramType="path"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=404, message="Not Found"),
     *          @Docs\ResponseMessage(code=204, message="No Content")
     *      )
     * )
     *
     * @param Request $request
     * @param integer $id
     * @return Response\NoContent
     * @throws Exception\NotFound
     */
    public function delete(Request $request, $id)
    {
        $repo = $this->serviceOrm()->getRepository('Api\Temperature\Entity\Temperature');

        if (is_null($temperature = $repo->find($id))) {
            throw new Exception\NotFound();
        }

        $this->serviceOrm()->remove($temperature);
        $this->serviceOrm()->flush();

        return new Response\NoContent();
    }

    /**
     * @Phprest\Route(method="POST", path="/temperatures")
     *
     * @Docs\Api(
     *      path="/temperatures",
     *      @Docs\Operation(
     *          method="POST", summary="Creates a new temperature",
     *          @Docs\Parameter(required=true, type="body", paramType="body"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=422, message="Unprocessable Entity"),
     *          @Docs\ResponseMessage(code=201, message="Created")
     *      )
     * )
     *
     * @param Request $request
     * @return Response\Created
     * @throws Exception\UnprocessableEntity
     */
    public function post(Request $request)
    {
        try {
            /** @var Entity\Temperature $temperature */
            $temperature = $this->deserialize('Api\Temperature\Entity\Temperature', $request);
        } catch (RuntimeException $e) {
            throw new Exception\UnprocessableEntity(0, [new Service\Validator\Entity\Error('', $e->getMessage())]);
        }

        if (count($errors = $this->getErrors($temperature))) {
            throw new Exception\UnprocessableEntity(0, $this->getFormattedErrors($errors));
        }

        $this->serviceOrm()->persist($temperature);
        $this->serviceOrm()->flush();

        return new Response\Created($request->getSchemeAndHttpHost() . '/temperatures/' . $temperature->id);
    }

    /**
     * @Phprest\Route(method="OPTIONS", path="/temperatures")
     *
     * @Docs\Api(
     *      path="/temperatures",
     *      @Docs\Operation(
     *          method="OPTIONS", summary="Gets allowed methods on temperatures",
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @return Response\Ok
     */
    public function optionsAll()
    {
        return new Response\Ok('', ['Allow' => 'GET,POST,OPTIONS']);
    }

    /**
     * @Phprest\Route(method="OPTIONS", path="/temperatures/{id:number}")
     *
     * @Docs\Api(
     *      path="/temperatures/{id}",
     *      @Docs\Operation(
     *          method="OPTIONS", summary="Gets allowed methods on a temperature",
     *          @Docs\Parameter(name="id", required=true, type="integer", paramType="path"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=404, message="Not Found"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @param Request $request
     * @param integer $id
     * @return Response\Ok
     * @throws Exception\NotFound
     */
    public function options(Request $request, $id)
    {
        $repo = $this->serviceOrm()->getRepository('Api\Temperature\Entity\Temperature');

        if (is_null($temperature = $repo->find($id))) {
            throw new Exception\NotFound();
        }

        return new Response\Ok('', ['Allow' => 'GET,DELETE,OPTIONS']);
    }
}
