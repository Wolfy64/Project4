<?php

namespace David\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Guest
 *
 * @ORM\Table(name="guest")
 * @ORM\Entity(repositoryClass="David\TicketBundle\Repository\GuestRepository")
 */
class Guest
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *      type = "string",
     *      message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *      type = "string",
     *      message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $lastName;

    /**
     * @var \Date
     * @ORM\Column(name="date_of_birth", type="date")
     * @Assert\NotBlank()
     * @Assert\Date(
     *      message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $dateOfBirth;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(
     *      type = "string",
     *      message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity="David\TicketBundle\Entity\Ticket", inversedBy="guest")
     * @Assert\Valid()
     */
    private $ticket;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Guest
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Guest
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set dateOfBirth
     *
     * @param string $dateOfBirth
     *
     * @return Guest
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Guest
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set ticket
     *
     * @param \David\TicketBundle\Entity\Ticket $ticket
     *
     * @return Guest
     */
    public function setTicket($ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \David\TicketBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}
