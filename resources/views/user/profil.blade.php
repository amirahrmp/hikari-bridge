@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Profile</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
    
    <div class="row mt-3">
        <div class="col-sm-12">
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <form action="{{ route('user.update_profil') }}" method="post" class="form-profil" data-toggle="validator" enctype="multipart/form-data">
                                @csrf
                                <div class="box-body">
                                    <div class="alert alert-info alert-dismissible" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <i class="icon fa fa-check"></i> Perubahan berhasil disimpan
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-lg-2 control-label">Nama</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="name" class="form-control" id="name" required autofocus value="{{ $profil->name }}">
                                            <span class="help-block with-errors"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="foto" class="col-lg-2 control-label">Foto Profil</label>
                                        <div class="col-lg-4">
                                            <input type="file" name="foto" class="form-control" id="foto"
                                                onchange="preview('.tampil-foto', this.files[0])">
                                            <span class="help-block with-errors"></span>
                                            <br>
                                            <div class="tampil-foto">
                                                <img src="{{ url($profil->foto ?? '/') }}" width="200">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="old_password" class="col-lg-2 control-label">Password Lama</label>
                                        <div class="col-lg-6">
                                            <input type="password" name="old_password" id="old_password" class="form-control" 
                                            minlength="6">
                                            <span class="help-block with-errors"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-lg-2 control-label">Password</label>
                                        <div class="col-lg-6">
                                            <input type="password" name="password" id="password" class="form-control" 
                                            minlength="6">
                                            <span class="help-block with-errors"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password_confirmation" class="col-lg-2 control-label">Konfirmasi Password</label>
                                        <div class="col-lg-6">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                                                data-match="#password">
                                            <span class="help-block with-errors"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer text-right">
                                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
            <!--/Datatable-->

        </div>
    </div>

</div>
    
@endsection

@push('scripts')
<script>
    $(function () {
        $('#old_password').on('keyup', function () {
            if ($(this).val() != "") $('#password, #password_confirmation').attr('required', true);
            else $('#password, #password_confirmation').attr('required', false);
        });

        $('.form-profil').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url: $('.form-profil').attr('action'),
                    type: $('.form-profil').attr('method'),
                    data: new FormData($('.form-profil')[0]),
                    async: false,
                    processData: false,
                    contentType: false
                })
                .done(response => {
                    $('[name=name]').val(response.name);
                    $('.tampil-foto').html(`<img src="{{ url('/') }}${response.foto}" width="200">`);
                    $('.img-profil').attr('src', `{{ url('/') }}/${response.foto}`);

                    $('.alert').fadeIn();
                    setTimeout(() => {
                        $('.alert').fadeOut();
                    }, 3000);
                })
                .fail(errors => {
                    if (errors.status == 422) {
                        alert(errors.responseJSON); 
                    } else {
                        alert('Tidak dapat menyimpan data');
                    }
                    return;
                });
            }
        });
    });
</script>
@endpush