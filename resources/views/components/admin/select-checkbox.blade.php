@props([
    'id' => null,
    'label' => 'Select row',
])

{{--
    Bound to the `bulkSelection` Alpine component on an ancestor element.
    When `id` is omitted the checkbox drives the select-all header control.
--}}
<input type="checkbox"
       @if ($id !== null)
           :checked="isSelected({{ $id }})"
           @change="toggle({{ $id }})"
       @else
           :checked="allSelected"
           x-effect="$el.indeterminate = someSelected"
           @change="toggleAll($event.target.checked)"
       @endif
       {{ $attributes->merge(['class' => 'cursor-pointer rounded border-slate-300 text-brand-teal transition focus:ring-2 focus:ring-brand-teal focus:ring-offset-0']) }}
       aria-label="{{ $label }}">
