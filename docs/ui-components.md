# UI Components

The UI foundation uses anonymous Blade components. All components forward HTML attributes, so they work with standard Blade attributes, Alpine directives, and Livewire directives such as `wire:model` and `wire:click`.

## Actions and feedback

```blade
<x-ui.button type="submit">Save</x-ui.button>
<x-ui.button variant="secondary" href="/users">Cancel</x-ui.button>
<x-ui.button variant="danger" wire:click="delete">Delete</x-ui.button>

<x-ui.badge variant="success">Active</x-ui.badge>

<x-ui.alert variant="danger" title="Unable to save" dismissible>
    Check the highlighted fields and try again.
</x-ui.alert>
```

Button variants are `primary`, `secondary`, `danger`, and `ghost`. Sizes are `sm`, `md`, and `lg`. Alert and badge variants include `info`, `success`, `warning`, and `danger`.

## Forms

Labels, controls, and errors are intentionally separate so screens can compose accessible descriptions and validation state explicitly.

```blade
<div class="space-y-2">
    <x-form.label for="email" required>Email</x-form.label>
    <x-form.input
        id="email"
        name="email"
        type="email"
        wire:model="form.email"
        :invalid="$errors->has('form.email')"
        aria-describedby="email-error"
    />
    <x-form.error id="email-error" :messages="$errors->get('form.email')" />
</div>
```

Textarea, select, and checkbox controls follow the same attribute-forwarding and `invalid` conventions.

## Cards

```blade
<x-ui.card>
    <x-slot:header>User details</x-slot:header>
    Card content
    <x-slot:footer>Updated just now</x-slot:footer>
</x-ui.card>
```

Advanced components such as modals, toasts, data tables, and date pickers are introduced only when their feature phase needs them.
