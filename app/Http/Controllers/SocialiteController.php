<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to the provider
     */
    public function redirect(string $provider): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        $response = Socialite::driver($provider)->user();

        $user = User::firstWhere(['email' => $response->getEmail()]);

        if ($user) {
            $user->update([$provider.'_id' => $response->getId()]);
        } else {
            $user = User::create([
                $provider.'_id' => $response->getId(),
                'name' => $response->getName(),
                'email' => $response->getEmail(),
                'password' => '',
            ]);
            $user->assignRole('guest');
        }

        auth()->login($user);

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    /**
     * Validate the provider
     *
     * @return array{provider: string}
     */
    protected function validateProvider(string $provider): array
    {
        /** @var array{provider: string} */
        return validator(
            ['provider' => $provider],
            ['provider' => 'in:google']
        )->validate();
    }
}
