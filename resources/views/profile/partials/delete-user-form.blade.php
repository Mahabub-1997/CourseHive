<section class="w-full max-w-md mx-auto mt-10">
    <div class="bg-white shadow-xl rounded-xl p-8">
        <header class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ __('Delete Account') }}
            </h2>

            <p class="mt-2 text-gray-600 text-sm">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>
        </header>

        <!-- Delete Button Centered -->
        <div class="flex justify-center">
            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="py-2 px-6 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition"
            >
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>

        <!-- Confirmation Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
                @csrf
                @method('delete')

                <h2 class="text-xl font-semibold text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="text-gray-600 text-sm">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div>
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500 transition"
                        placeholder="{{ __('Password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</section>
