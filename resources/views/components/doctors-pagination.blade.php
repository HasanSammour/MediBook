<div class="pagination-wrapper">
    <div class="pagination-info">
        Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} results
    </div>
    <div class="pagination-container">
        {{ $doctors->appends(request()->query())->links() }}
    </div>
</div>