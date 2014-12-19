<?php namespace Api\Camera\Entity;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as Docs;
use Doctrine\ORM\Mapping as Orm;

/**
 * @Serializer\XmlRoot("camera")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route("/camera", absolute = true)
 * )
 * @Hateoas\Relation(
 *      "transition.on::post",
 *      href = @Hateoas\Route("/camera?transition=on", absolute = true),
 *      exclusion = @Hateoas\Exclusion(excludeIf = "expr('on' === object.getState())"))
 * )
 * @Hateoas\Relation(
 *      "transition.off::post",
 *      href = @Hateoas\Route("/camera?transition=off", absolute = true),
 *      exclusion = @Hateoas\Exclusion(excludeIf = "expr('off' === object.getState())"))
 * )
 *
 * @Orm\Entity
 * @Orm\Table(name="camera")
 *
 * @Docs\Model(id="camera")
 */
class Camera
{
    const STATE_ON = 'on';
    const STATE_OFF = 'off';

    /**
     * @var string
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices={"on", "off"}, message="It must be on/off.")
     *
     * @Docs\Property(name="state", type="string", required=true)
     *
     * @Orm\Id()
     * @Orm\Column(type="string", length=8, columnDefinition="ENUM('on', 'off')")
     */
    private $state;

    /**
     * @param string $state
     */
    public function __construct($state = self::STATE_OFF)
    {
        $this->setState($state);
    }

    /**
     * @param $state
     * @throws \InvalidArgumentException
     */
    public function setState($state)
    {
        if ( ! in_array($state, [self::STATE_OFF, self::STATE_ON])) {
            throw new \InvalidArgumentException('Wrong state');
        }

        $this->littleFsm($state);

        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param $targetState
     * @throws \Exception
     */
    protected function littleFsm($targetState)
    {
        if ($this->state === self::STATE_OFF && $targetState === self::STATE_OFF) {
            throw new \Exception('Wrong state transferring Off -> Off');
        } elseif ($this->state === self::STATE_ON && $targetState === self::STATE_ON) {
            throw new \Exception('Wrong state transferring On -> On');
        }
    }
}
