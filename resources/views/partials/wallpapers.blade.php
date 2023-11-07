<div class="m-0 m-md-4 my-4 m-md-0">
    <div class="row">
        <div class="col-sm-0 col-md-2 col-lg-2 col-xl-2 text-right">
        </div>
        <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 mx-auto">
            <input class="form-control" name="searchInput" id="searchInput" placeholder="{{trans('Search')}}" value="{{old('name')}}">
        </div>
        <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2 text-right">
            <button class="btn btn-secondary btn-sm mr-2" id="btnListView">
                <i class="fa fa-list"></i>
            </button>
            <button class="btn btn-secondary btn-sm" id="btnGridView">
                <i class="fa fa-th"></i>
            </button>
        </div>
    </div>
</div>

<div id="listView">
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="tableWallpapers" style="width: 100%">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('Wallpaper')</th>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Tags')</th>
                        <th scope="col">@lang('Extension')</th>
                        <th scope="col">@lang('Type')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody class="loadListWallpapers">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="gridView">
    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row image-gallery loadGridWallpapers">
        </div>
    </div>
</div>

<div class="m-0 m-md-4 my-4 m-md-0" id="pagination"> </div>



