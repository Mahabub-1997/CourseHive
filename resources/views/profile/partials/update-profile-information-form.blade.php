<section class="w-full mt-10">
    <div class="w-full bg-white shadow-lg p-8 rounded-lg">
        {{-- Header --}}
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-2 text-gray-600 text-sm">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </header>

        {{-- Email Verification Form (Hidden) --}}
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        {{-- Profile Update Form --}}
        <form method="post"
              action="{{ route('profile.update') }}"
              class="space-y-6 w-full"
              enctype="multipart/form-data">

            @csrf
            @method('patch')

            {{-- Name --}}
            <div class="w-full">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text"
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg
                                     focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 transition"
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div class="w-full mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email"
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg
                                     focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 transition"
                              :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                {{-- If email is unverified --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            {{-- Resend Verification --}}
                            <button form="send-verification"
                                    class="underline text-sm text-indigo-600 hover:text-indigo-900
                                           rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2
                                           focus:ring-indigo-500 transition">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        {{-- Success Message --}}
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Phone --}}
            <div class="w-full mt-4">
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="text"
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg
                                     focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 transition"
                              :value="old('phone', $user->phone)" required autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Profile Image --}}
            <div class="w-full mt-4">
                <x-input-label for="profile_image" :value="__('Profile Image')" />
                <input id="profile_image" name="profile_image" type="file"
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg
                              cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-400
                              focus:border-indigo-500 transition"
                       accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />

                {{-- Show current profile image --}}
                @if($user->profile_image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->profile_image) }}"
                             alt="Profile Image"
                             class="w-20 h-20 rounded-full object-cover border">
                    </div>
                @endif
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center gap-4">
                <x-primary-button
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2
                           rounded-lg transition">
                    {{ __('Save') }}
                </x-primary-button>

                {{-- Success message --}}
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 font-medium">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>
