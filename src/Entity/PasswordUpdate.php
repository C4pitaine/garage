<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    #[Assert\NotBlank(message:"Le champ ne peut pas être vide")]
    private ?string $oldPassword = null;

    #[Assert\Length(min:6,max:255,minMessage:"Votre mot de passe doit faire plus de 6 caractères",maxMessage:"Votre mot de passe ne dois pas dépasser les 255 caractères")]
    private ?string $newPassword = null;

    #[Assert\EqualTo(propertyPath:'newPassword',message:"La confirmation doit correspondre au nouveau mot de passe")]
    private ?string $confirmPassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): static
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): static
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): static
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
