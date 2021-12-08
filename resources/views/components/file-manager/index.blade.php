@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/filemanager/css/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/filemanager/css/dropzone.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/filemanager/css/mime-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/filemanager/css/file-manager.css') }}">
@endpush

<nav class="navbar sticky-top navbar-expand-lg navbar-dark" id="nav">
    <a class="navbar-brand invisible-lg d-none d-lg-inline" id="to-previous">
      <i class="fa fa-arrow-left fa-fw"></i>
      <span class="d-none d-lg-inline">{{ translate('lfm.nav-back') }}</span>
    </a>
    <a class="navbar-brand d-block d-lg-none" id="show_tree">
      <i class="fa fa-bars fa-fw"></i>
    </a>
    <a class="navbar-brand d-block d-lg-none" id="current_folder"></a>
    <a id="loading" class="navbar-brand"><i class="fa fa-spinner fa-spin"></i></a>
    <div class="ml-auto px-2">
      <a class="navbar-link d-none" id="multi_selection_toggle">
        <i class="fa fa-check-square-o fa-fw"></i>
        <span class="d-none d-lg-inline">{{ translate('lfm.menu-multiple') }}</span>
      </a>
    </div>
    <a class="navbar-toggler collapsed border-0 px-1 py-2 m-0" data-toggle="collapse" data-target="#nav-buttons">
      <i class="fa fa-cog fa-fw"></i>
    </a>
    <div class="collapse navbar-collapse flex-grow-0" id="nav-buttons">
      <ul class="navbar-nav">
        @foreach (config('file-manager.actions') as $item)
          <li class="nav-item d-none">
            <a class="nav-link px-2" data-action="{{$item['name']}}" data-multiple="{{$item['multiple']}}">
              <i class="fa fa-fw fa-{{$item['icon']}}"></i>
              <span>{{ translate('lfm.'.$item['label']) }}</span>
            </a>
          </li>
        @endforeach
        <li class="nav-item">
          <a class="nav-link px-2" data-display="grid">
            <i class="fa fa-th-large fa-fw"></i>
            <span>{{ translate('lfm.nav-thumbnails') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-2" data-display="list">
            <i class="fa fa-list-ul fa-fw"></i>
            <span>{{ translate('lfm.nav-list') }}</span>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-sort fa-fw"></i>{{ translate('lfm.nav-sort') }}
          </a>
          <div class="dropdown-menu dropdown-menu-right border-0">
            @foreach (config('file-manager.sortings') as $item)
                <a class="dropdown-item" data-sortby="{{$item['by']}}">
                    <i class="fa fa-fw fa-{{$item['icon']}}">
                        <span>@lang('bootstrap::lfm.'.$item['label'])</span>
                    </i>
                </a>
            @endforeach
          </div>
        </li>
      </ul>
    </div>
</nav>

<nav class="bg-dark d-flex fixed-bottom border-top d-none" id="actions">
    <a class="px-2 py-1" data-action="open" data-multiple="false"><i class="fa fa-folder-open mr-1"></i>{{ translate('lfm.btn-open') }}</a>
    <a class="px-2 py-1" data-action="preview" data-multiple="true"><i class="fa fa-image mr-1"></i>{{ translate('lfm.menu-view') }}</a>
    <a class="px-2 py-1" data-action="use" data-multiple="true"><i class="fa fa-check mr-1"></i>{{ translate('lfm.btn-confirm') }}</a>
</nav>

<div class="container-fluid">
    <div class="row">
        <div id="tree" class="col-3 bg-dark"></div>

        <div id="main" class="col-9">
        <div id="alerts"></div>

        <nav aria-label="breadcrumb" class="d-none d-lg-block" id="breadcrumbs">
            <ol class="breadcrumb bg-secondary">
            <li class="breadcrumb-item invisible">Home</li>
            </ol>
        </nav>

        <div id="empty" class="text-white d-none">
            <i class="fa fa-folder-open"></i>
            {{ translate('lfm.message-empty') }}
        </div>

        <div id="content"></div>
        <div id="pagination"></div>

        <a id="item-template" class="d-none">
            <div class="square"></div>

            <div class="info">
            <div class="item_name text-truncate"></div>
            <time class="text-muted font-weight-light text-truncate"></time>
            </div>
        </a>
        </div>

        <div id="fab"></div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">{{ translate('lfm.title-upload') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('lfm.upload') }}" role='form' id='uploadForm' name='uploadForm' method='post' enctype='multipart/form-data' class="dropzone bg-transparent">
            <div class="form-group" id="attachment">
              <div class="controls text-center">
                <div class="input-group w-100">
                  <a class="btn btn-primary w-100 text-white" id="upload-button">{{ translate('lfm.message-choose') }}</a>
                </div>
              </div>
            </div>
            <input type='hidden' name='working_dir' id='working_dir'>
            <input type='hidden' name='type' id='type' value='{{ request("type") }}'>
            <input type='hidden' name='_token' value='{{csrf_token()}}'>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('lfm.btn-close') }}</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="notify" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('lfm.btn-close') }}</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">{{ translate('lfm.btn-confirm') }}</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('lfm.btn-close') }}</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">{{ translate('lfm.btn-confirm') }}</button>
        </div>
      </div>
    </div>
</div>

<div id="carouselTemplate" class="d-none carousel slide bg-light" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#previewCarousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <a class="carousel-label"></a>
        <div class="carousel-image"></div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#previewCarousel" role="button" data-slide="prev">
      <div class="carousel-control-background" aria-hidden="true">
        <i class="fa fa-chevron-left"></i>
      </div>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#previewCarousel" role="button" data-slide="next">
      <div class="carousel-control-background" aria-hidden="true">
        <i class="fa fa-chevron-right"></i>
      </div>
      <span class="sr-only">Next</span>
    </a>
</div>

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('vendor/laravel-filemanager/js/cropper.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-filemanager/js/dropzone.min.js') }}"></script>
<script>
var lang = {!! json_encode(trans(config('vnnit-core.prefix').'::lfm')) !!};
</script>
<script src="{{ asset('vendor/filemanager/js/file-manager.js') }}"></script>
<script>
Dropzone.options.uploadForm = {
    paramName: "upload[]", // The name that will be used to transfer the file
    uploadMultiple: false,
    parallelUploads: 5,
    timeout:0,
    clickable: '#upload-button',
    dictDefaultMessage: lang['message-drop'],
    init: function() {
    var _this = this; // For the closure
    this.on('success', function(file, response) {
        if (response == 'OK') {
        loadFolders();
        } else {
        this.defaultOptions.error(file, response.join('\n'));
        }
    });
    },
    headers: {
    'Authorization': 'Bearer ' + getUrlParam('token')
    },
    acceptedFiles: "{{ implode(',', $helper->availableMimeTypes()) }}",
    maxFilesize: ({{ $helper->maxUploadSize() }} / 1000)
}
</script>
@endpush
