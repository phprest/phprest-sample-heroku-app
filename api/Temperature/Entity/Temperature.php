<?php namespace Api\Temperature\Entity;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as Docs;
use Doctrine\ORM\Mapping as Orm;

/**
 * @Serializer\XmlRoot("temperature")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route("/temperatures", parameters = {"id" = "expr(object.id)"}, absolute = true)
 * )
 *
 * @Orm\Entity
 * @Orm\Table(name="temperatures")
 *
 * @Docs\Model(id="temperature")
 */
class Temperature
{
    /**
     * @var integer
     * @Serializer\Type("integer")
     *
     * @Assert\Range(min = 1)
     *
     * @Orm\Id()
     * @Orm\Column(type="integer", options={"unsigned"=true})
     * @Orm\GeneratedValue()
     *
     * @Docs\Property(name="id", type="integer")
     */
    public $id;

    /**
     * @var string
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices={"celsius"}, message="It must be celsius.")
     *
     * @Docs\Property(name="unit", type="string", enum="['celsius']", required=true)
     */
    public $unit = "celsius";

    /**
     * @var integer
     * @Serializer\Type("integer")
     *
     * @Assert\NotBlank()
     * @Assert\Range(min=-100, max=100)
     *
     * @Orm\Column(type="smallint")
     *
     * @Docs\Property(name="value", type="integer", required=true)
     */
    public $value;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     *
     * @Orm\Column(type="datetime")
     *
     * @Docs\Property(name="created", type="string", required=true, description="ISO 8601")
     */
    public $created;

    /**
     * @param integer $id
     * @param integer $value
     * @param \DateTime $created
     */
    public function __construct($id = null, $value = null, \DateTime $created = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->created = $created;
    }
}
