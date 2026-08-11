@props(['paginator'])

@if($paginator->hasPages())
<div class="px-5 py-4 border-t border-gray-700">
    {{ $paginator->links() }}
</div>
@endif
