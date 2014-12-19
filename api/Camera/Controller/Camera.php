<?php namespace Api\Camera\Controller;

use Phprest\Util\Controller;
use Symfony\Component\HttpFoundation\Request;
use Phprest\Response;
use Api\Camera\Entity;
use Phprest\Exception;
use Phprest\Service;
use Swagger\Annotations as Docs;
use Phprest\Annotation as Phprest;

/**
 * @Docs\Resource(
 *      apiVersion="0.1",
 *      swaggerVersion="0.9.6",
 *      resourcePath="/camera",
 *      basePath="/"
 * )
 */
class Camera extends Controller
{
    use Service\Hateoas\Getter, Service\Orm\Getter, Service\RequestFilter\Getter;
    use Service\Hateoas\Util, Service\Validator\Util, Service\RequestFilter\Util;

    /**
     * @Phprest\Route(method="GET", path="/camera")
     *
     * @Docs\Api(
     *      path="/camera",
     *      @Docs\Operation(
     *          method="GET", summary="Gets the camera",
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=404, message="Not Found"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @param Request $request
     * @return Response\Ok
     * @throws Exception\NotFound
     */
    public function get(Request $request)
    {
        $repo = $this->serviceOrm()->getRepository('Api\Camera\Entity\Camera');

        if (is_null($camera = $repo->findOneBy([]))) {
            throw new Exception\NotFound();
        }

        return new Response\Ok($camera);
    }

    /**
     * @Phprest\Route(method="POST", path="/camera")
     *
     * @Docs\Api(
     *      path="/camera",
     *      @Docs\Operation(
     *          method="POST", summary="State transition for camera",
     *          @Docs\Parameter(name="transition", required=true, type="string", paramType="query", description="on,off"),
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=400, message="Bad Request"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @param Request $request
     * @return Response\Created
     * @throws Exception\BadRequest
     */
    public function post(Request $request)
    {
        $transition = $request->query->get('transition');
        $repo = $this->serviceOrm()->getRepository('Api\Camera\Entity\Camera');

        try {
            $camera = $repo->findOneBy([]);

            if (is_null($camera)) {
                $camera = new Entity\Camera($transition);
            } else {
                $camera->setState($transition);
            }

            $this->serviceOrm()->persist($camera);
            $this->serviceOrm()->flush();

        } catch (\Exception $e) {
            throw new Exception\BadRequest(0, [$e->getMessage()]);
        }

        return new Response\Ok($camera);
    }

    /**
     * @Phprest\Route(method="OPTIONS", path="/camera")
     *
     * @Docs\Api(
     *      path="/camera",
     *      @Docs\Operation(
     *          method="OPTIONS", summary="Gets allowed methods on the camera",
     *          @Docs\Parameter(name="accept", required=false, type="string", paramType="header"),
     *          @Docs\ResponseMessage(code=200, message="Ok")
     *      )
     * )
     *
     * @return Response\Ok
     */
    public function options()
    {
        return new Response\Ok('', ['Allow' => 'GET,POST,OPTIONS']);
    }
}
