<ul class="nav nav-pills flex-column">
  @foreach($root_folders as $root_folder)
    <li class="nav-item">
      <a class="nav-link" href="#" data-type="0" data-path="{{ $root_folder->url }}">
        <i class="fa fa-folder fa-fw"></i> {{ $root_folder->name }}
      </a>
    </li>
    @foreach($root_folder->children as $directory)
    <li class="nav-item sub-item">
      <a class="nav-link" href="#" data-type="0" data-path="{{ $directory->url }}">
        <i class="fa fa-folder fa-fw"></i> {{ $directory->name }}
      </a>
    </li>
    @endforeach
  @endforeach
</ul>

<div class="m-3">
    <h2>File Manager</h2>
    <div class="row mt-3">
        <div class="col-4">
            <img src="{{ asset('vendor/laravel-filemanager/img/152px color.png') }}" class="w-100">
        </div>
        <div class="col-8">
            <small class="d-block">Ver 1.0</small>
        </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">
        <p class="m-0">Current usage :</p>
        @php
          //    $f = 'D:\Wamp.NET\sites\vnnitsoft_new';
          //    $cmd = "powershell -command '(ls $f -r | measure -s Length).Sum'";
          //    $output = null;
          //    $in = null;
          //     $io = exec( $cmd, $output, $in);
          //     // dd(fread(popen($cmd, 'r'), 40960));
          //     // print_r(shell_exec("(ls $f -r | measure -s Length).Sum"))
          //     print_r($output);
          //     // $size = fgets ( $io, 4096);
          //     // $size = substr ( $size, 0, strpos ( $size, "\t" ) );
          //     // pclose ( $io );
          //     // echo 'Directory: ' . $f . ' => Size: ' . $size;
        @endphp
        <p class="m-0">{{size_format($used_disk)}} (Max : {{size_format($total_disk)}})</p>
      </div>
    </div>
    <div class="progress mt-3" style="height: .5rem;">
      <div style="width: {{($used_disk * 100) / $total_disk}}%" class="progress-bar progress-bar-striped progress-bar-animated bg-main" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
