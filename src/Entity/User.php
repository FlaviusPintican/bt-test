<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", name="expired_at", nullable=true)
     */
    private $expiredAt;

    /**
     * @ORM\Column(type="boolean", name="is_first_used", nullable=true)
     */
    private $isFirstUsed;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpiredAt(): ?DateTimeInterface
    {
        return $this->expiredAt;
    }

    /**
     * @param DateTimeInterface $expiredAt
     * @return $this
     */
    public function setExpiredAt(DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getIsFirstUsed(): ?bool
    {
        return $this->isFirstUsed;
    }

    /**
     * @param bool $isFirstUsed
     * @return $this
     */
    public function setIsFirstUsed(bool $isFirstUsed) : self
    {
        $this->isFirstUsed = $isFirstUsed;

        return $this;
    }
}
