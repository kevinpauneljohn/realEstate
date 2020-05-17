<aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
    @yield('right-sidebar')
    <div class="p-3 control-sidebar-content">
        <h5>Quick Tools</h5>
        <hr class="mb-2">
        <div class="mb-1">
            <input type="radio" name="tools" value="canned" class="mr-1"><span>Canned Message</span>
        </div>
        <div class="mb-4">
            <input type="radio" name="tools" value="computation" class="mr-1"><span>Sample Computation</span>
        </div>
        <h6>Navbar Variants</h6>
        <div class="d-flex">
            <div class="d-flex flex-wrap mb-3">
                test
            </div>
        </div>
    </div>
</aside>
