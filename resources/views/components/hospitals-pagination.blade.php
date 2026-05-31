<div class="pagination-wrapper">
    <div class="pagination-info">
        Showing {{ $hospitals->firstItem() }} to {{ $hospitals->lastItem() }} of {{ $hospitals->total() }} results
    </div>
    <div class="pagination-container">
        {{ $hospitals->appends(request()->query())->links() }}
    </div>
</div>