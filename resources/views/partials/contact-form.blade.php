@if (session('status'))
    <div class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        Please fix the highlighted fields and try again.
    </div>
@endif

<form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
    @csrf

    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-stone-900">Name *</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name') }}"
            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            required
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-stone-900">Email *</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            required
        >
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="phone" class="mb-2 block text-sm font-medium text-stone-900">Phone</label>
            <input
                id="phone"
                name="phone"
                type="text"
                value="{{ old('phone') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="partner_name" class="mb-2 block text-sm font-medium text-stone-900">Partner name</label>
            <input
                id="partner_name"
                name="partner_name"
                type="text"
                value="{{ old('partner_name') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('partner_name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="wedding_date" class="mb-2 block text-sm font-medium text-stone-900">Wedding date</label>
            <input
                id="wedding_date"
                name="wedding_date"
                type="date"
                value="{{ old('wedding_date') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('wedding_date')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="budget_range" class="mb-2 block text-sm font-medium text-stone-900">Budget range</label>
            <input
                id="budget_range"
                name="budget_range"
                type="text"
                value="{{ old('budget_range') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('budget_range')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="location" class="mb-2 block text-sm font-medium text-stone-900">Location</label>
            <input
                id="location"
                name="location"
                type="text"
                value="{{ old('location') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('location')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="venue" class="mb-2 block text-sm font-medium text-stone-900">Venue</label>
            <input
                id="venue"
                name="venue"
                type="text"
                value="{{ old('venue') }}"
                class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            >
            @error('venue')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <p class="mb-3 block text-sm font-medium text-stone-900">Services requested</p>

        @php
            $selectedServices = old('services_requested', []);
        @endphp

        <div class="grid gap-3 sm:grid-cols-2">
            @foreach (['Wedding Film', 'Teaser Film', 'Speeches', 'Drone Coverage', 'Full Ceremony Edit', 'Additional Coverage'] as $service)
                <label class="flex items-center gap-3 rounded-md border border-stone-200 px-4 py-3 text-sm text-stone-700">
                    <input
                        type="checkbox"
                        name="services_requested[]"
                        value="{{ $service }}"
                        @checked(in_array($service, $selectedServices))
                        class="rounded border-stone-300 text-stone-900 focus:ring-stone-900"
                    >
                    <span>{{ $service }}</span>
                </label>
            @endforeach
        </div>

        @error('services_requested')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @error('services_requested.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="how_did_you_hear" class="mb-2 block text-sm font-medium text-stone-900">How did you hear about me?</label>
        <input
            id="how_did_you_hear"
            name="how_did_you_hear"
            type="text"
            value="{{ old('how_did_you_hear') }}"
            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
        >
        @error('how_did_you_hear')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="message" class="mb-2 block text-sm font-medium text-stone-900">Message *</label>
        <textarea
            id="message"
            name="message"
            rows="6"
            class="w-full rounded-md border border-stone-300 px-4 py-3 text-sm text-stone-900 focus:border-stone-900 focus:outline-none"
            required
        >{{ old('message') }}</textarea>
        @error('message')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button
            type="submit"
            class="inline-flex rounded-md bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700"
        >
            Send inquiry
        </button>
    </div>
</form>