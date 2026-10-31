<?php

namespace App\Support;

use App\Models\User;

final class AdminMailRecipient
{
    public function resolve(): ?string
    {
        $configuredEmail = config('services.storefront.admin_orders_email');
        $email = is_string($configuredEmail) && filter_var($configuredEmail, FILTER_VALIDATE_EMAIL)
            ? $configuredEmail
            : User::query()->oldest('created_at')->value('email');

        return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
