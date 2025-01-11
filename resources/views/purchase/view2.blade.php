@extends('main')

@section('title', 'Pembelian')

@section('content')

<!-- Sweet Alert -->
@if(isset($status_hapus))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Hapus Data Berhasil',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        </script>
@endif

<!--Content right-->
<div class="col-sm-9 col-xs-12 content pt-3 pl-0">
    <h5 class="mb-0" ><strong>Pembelian</strong></h5>
    <span class="text-secondary">Dashboard <i class="fa fa-angle-right"></i> Pembelian</span>
    
    <div class="row mt-3">
        <div class="col-sm-12">
            <!--Datatable-->
            <div class="mt-1 mb-3 p-3 button-container bg-white border shadow-sm">
                <h6 class="mb-2">Pembelian Bahan Baku</h6>
                
                <!-- Tombol Tambah Data -->
                <a href="#" class="btn btn-primary btn-icon-split btn-sm tampilmodaltambah" data-toogle="modal" data-target="#ubahModal">
                    <span class="icon text-white-50">
                        <i class="ti ti-plus"></i>
                    </span>
                    <span class="text">Tambah Data</span>
                </a>
                <!-- Akghir Tombol Tambah Data -->


        <div class="card-body" id="show_all_purchases">
          
        </div>


    </div>
                
    <!--Footer-->
    <div class="row mt-5 mb-4 footer">
        <div class="col-sm-8">
            <span>&copy; All rights reserved 2019 designed by <a class="text-info" href="#">A-Fusion</a></span><br>
            <span>&copy; 2024 Modified by <a class="text-info" href="#">Kamal Sa'danah</a></span>
        </div>
        <div class="col-sm-4 text-right">
            <a href="#" class="ml-2">Contact Us</a>
            <a href="#" class="ml-2">Support</a>
        </div>
    </div>
    <!--Footer-->

</div>

<!-- Script untuk menampilkan modals -->
        <script>
            function deleteConfirm(e){
                var tomboldelete = document.getElementById('btn-delete')  
                id = e.getAttribute('data-id');

                // const str = 'Hello' + id + 'World';
                var url3 = "{{url('purchase/destroy/')}}";
                var url4 = url3.concat("/",id);
                // console.log(url4);

                // console.log(id);
                // var url = "{{url('perusahaan/destroy/"+id+"')}}";
                
                // url = JSON.parse(rul.replace(/"/g,'"'));
                tomboldelete.setAttribute("href", url4); //akan meload kontroller delete

                var pesan = "Data dengan ID <b>"
                var pesan2 = " </b>akan dihapus"
                var res = id;
                document.getElementById("xid").innerHTML = pesan.concat(res,pesan2);

                var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {  keyboard: false });
                
                myModal.show();
            
            }
        </script>

        <!-- Awal Delete Confirmation-->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                </div>
                <div class="modal-body" id="xid"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
                    
                </div>
                </div>
            </div>
        </div> 
        <!-- Akhir Delete Confirmation -->

<!-- Awal Modal Ubah dan Tambah -->
<div class="modal fade" id="ubahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="labelmodalubah">Ubah Data Purchase</h5>
        </div>
        
        <div class="modal-body">
            <!-- Form untuk input -->
            <form action="#" class="formubahpurchase" method="post">
            @csrf
            <input type="hidden" id="idpurchasehidden" name="idpurchasehidden" value="">
            <input type="hidden" id="tipeproses" name="tipeproses" value="">
                <div class="mb-3 row">
                    <label for="nomerlabel" class="col-sm-4 col-form-label">Supplier</label>
                        <div class="col-sm-8">
                            <select class="form-control" aria-label="Default select example" id="nama_perusahaan" name="nama_perusahaan">
                                @foreach ($supplier as $p)
                                    <option value="{{$p->id}}">{{$p->nama_perusahaan}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback errornama_perusahaan"></div>
                        </div>    
                </div>

                <div class="mb-3 row">
                    <label for="nomerlabel" class="col-sm-4 col-form-label">Kode Purchase</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kode_bahanbaku" name="kode_bahanbaku" placeholder="cth: BB-001">
                        <div class="invalid-feedback errorkode_bahanbaku"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="lantailabel" class="col-sm-4 col-form-label">Tanggal Pembelian</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli">
                        <div class="invalid-feedback errornama_bahanbaku"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="lantailabel" class="col-sm-4 col-form-label">Nama Bahan Baku</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_bahanbaku" name="nama_bahanbaku">
                        <div class="invalid-feedback errornama_bahanbaku"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hargalabel" class="col-sm-4 col-form-label">Harga</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="harga" name="harga" >
                        <div class="invalid-feedback errorharga"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hargalabel" class="col-sm-4 col-form-label">Quantity</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="quantity" name="quantity" >
                        <div class="invalid-feedback errorquantity"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hargalabel" class="col-sm-4 col-form-label">Total</label>
                    <div class="col-sm-8">
                        <input type="number" value="" name="total" readonly class="form-control total">
                        <div class="invalid-feedback errortotal"></div>
                    </div>
                </div>
            </div>    

            <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success btnsimpan">Simpan</button>
            
            </div>
        </div>
    </div>
</div>   
<!-- Akhir Ubah dan Tambah Data Menggunakan Modal -->

<script>
    $(document).ready(function(){
        $('#harga, #quantity').on('input', function() {
            var harga = $('#harga').val();
            var quantity = $('#quantity').val();
            if (harga && quantity) {
                var total = harga * quantity;
                $('.total').val(total);
            } else {
                $('.total').val('');
            }
        });
    });
</script>

<!-- Jquery Proses Ubah / Tambah Data -->
<!-- Modal Tambah Pop Up versi 2 -->

<!-- Ketika tombol dengan elemen id tampilmodaltambah ditekan -->
<script>
      $(function(){
            $('.tampilmodaltambah').on('click', function(){
              // merubah label menjadi Tambah Data Kamar
              $('#labelmodalubah').html('Tambah Data Purchase');
              url = "{{url('purchase')}}";
              $('.formubahpurchase').attr('action',url);
            //   $('#idcoa').val(12);

              // kosongkan isi dari input form
              $('#kode_bahanbaku').val('');
              $('#tanggal_beli').val('');
              $('#nama_bahanbaku').val('');
              $('#harga').val('');
              $('#quantity').val('');
              $('#total').val('');
              $('#idpurchaseahidden').val('');
              $('#tipeproses').val('tambah'); //untuk identifikasi di controller apakah tambah atau update


                var data = {
                    'kode_bahanbaku': $('.kode_bahanbaku').val(),
                    'tanggal_beli': $('.tanggal_beli').val(),
                    'nama_bahanbaku': $('.nama_bahanbaku').val(),
                    'nama_perusahaan': $('.nama_perusahaan').val(),
                    'harga': $('.harga').val(),
                    'quantity': $('.quantity').val(),
                    'total': $('.total').val(),
                }  

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

              $('#ubahModal').modal('show');
              
            //   const id = $(this).data('id');
              $.ajax(
                {
                  
                    type: "post", //isinya put untuk update dan post untuk insert
                    url: "{{url('purchase')}}",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 400) {
                            if(response.errors.kode_bahanbaku){
                                $('#kode_bahanbaku').removeClass('is-valid').addClass('is-invalid');
                                $('.errorkode_bahanbaku').html(response.errors.kode_bahanbaku);
                            }else{
                                $('#kode_bahanbaku').removeClass('is-invalid').addClass('is-valid');
                                $('.errorkode_bahanbaku').html();
                            }

                            if(response.errors.tanggal_beli){
                                $('#tanggal_beli').removeClass('is-valid').addClass('is-invalid');
                                $('.errortanggal_beli').html(response.errors.tanggal_beli);
                            }else{
                                $('#tanggal_beli').removeClass('is-invalid').addClass('is-valid');
                                $('.errortanggal_beli').html();
                            }

                            if(response.errors.nama_bahanbaku){
                                $('#nama_bahanbaku').removeClass('is-valid').addClass('is-invalid');
                                $('.errornama_bahanbaku').html(response.errors.nama_bahanbaku);
                            }else{
                                $('#nama_bahanbaku').removeClass('is-valid').removeClass('is-invalid').addClass('is-valid');
                                $('.errornama_bahanbaku').html();
                            }

                            if(response.errors.nama_perusahaan){
                                $('#nama_perusahaan').removeClass('is-valid').addClass('is-invalid');
                                $('.errornama_perusahaan').html(response.errors.nama_perusahaan);
                            }else{
                                $('#nama_perusahaan').removeClass('is-invalid').addClass('is-valid');
                                $('.errornama_perusahaan').html();
                            }

                            if(response.errors.harga){
                                $('#harga').removeClass('is-valid').addClass('is-invalid');
                                $('.errorharga').html(response.errors.harga);
                            }else{
                                $('#harga').removeClass('is-invalid').addClass('is-valid');
                                $('.errorharga').html();
                            }

                            if(response.errors.quantity){
                                $('#quantity').removeClass('is-valid').addClass('is-invalid');
                                $('.errorquantity').html(response.errors.quantity);
                            }else{
                                $('#quantity').removeClass('is-invalid').addClass('is-valid');
                                $('.errorquantity').html();
                            }

                            if(response.errors.total){
                                $('#total').removeClass('is-valid').addClass('is-invalid');
                                $('.errortotal').html(response.errors.total);
                            }else{
                                $('#total').removeClass('is-invalid').addClass('is-valid');
                                $('.errortotal').html();
                            }

                           

                        } else {
                            
                            // munculkan pesan sukses
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.sukses,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            
                            // kosongkan form
                            $('#ubahModal').modal('hide');

                            
                            datapurchase(); //refresh data coa
                            
                        }
                    }

                }
              ); 

            });
          }); 
</script>
<!-- Akhir Jquery Proses Ubah / Tambah Data -->

<!-- Ketika tombol dengan elemen class bernama  editbtn ditekan -->
<script>
        
      function updateConfirm(e){
        id = e.getAttribute('data-id');

        $('#labelmodalubah').html('Ubah Data Purchase');
        url = "{{url('purchase')}}";
        $('.formubahpurchase').attr('action',url);
        $('#idpurchasehidden').val(id);
        $('#tipeproses').val('ubah'); 
        $('#ubahModal').modal('show');

        var url3 = "{{url('purchase/edit/')}}";
        var url4 = url3.concat("/",id);

        $.ajax({
            type: "GET",
            url: url4,
            success: function (response) {
                if (response.status == 404) {
                    // beri alert kalau gagal
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });

                    $('#ubahModal').modal('hide');
                } else {
                    // console.log(response.coa.kode_akun);
                    $('#kode_bahanbaku').val(response.purchase.kode_bahanbaku);
                    $('#tanggal_beli').val(response.purchase.tanggal_beli);
                    $('#nama_bahanbaku').val(response.purchase.nama_bahanbaku);
                    $('#nama_perusahaan').val(response.purchase.nama_perusahaan);
                    $('#harga').val(response.purchase.harga);
                    $('#quantity').val(response.purchase.quantity);
                    $('#total').val(response.purchase.total);
                    $('#idpurchasehidden').val(id)

                    // pastikan form is-invalid dikembalikan ke valid
                    $('#kode_bahanbaku').removeClass('is-invalid').addClass('is-valid');;
                    $('.errorkode_bahanbaku').html();
                    $('#tanggal_beli').removeClass('is-invalid').addClass('is-valid');;
                    $('.errortanggal_beli').html();
                    $('#nama_bahanbaku').removeClass('is-invalid').addClass('is-valid');;
                    $('.errornama_bahanbaku').html();
                    $('#nama_perusahaan').removeClass('is-invalid').addClass('is-valid');;
                    $('.errornama_perusahaan').html();
                    $('#harga').removeClass('is-invalid').addClass('is-valid');;
                    $('.errorharga').html();
                    $('#quantity').removeClass('is-invalid').addClass('is-valid');;
                    $('.errorquantity').html();
                    $('#total').removeClass('is-invalid').addClass('is-valid');;
                    $('.errortotal').html();
                    
                }
            }
        });
      } 
</script>
<!-- Akhir Ketika tombol dengan elemen class bernama  editbtn ditekan -->

<!-- Proses mengisi data pada tabel -->
<script>
        function datapurchase(){
            $.ajax({

                url: 'purchase/fetchAll',
                method: 'get',
                success: function(response) {
                    $("#show_all_purchases").html(response);
                    $("table").DataTable({
                    order: [0, 'desc']
                    });
                }
            });
        }
        
    </script>
    <script>
        // $.noConflict();
        $(document).ready(function(){
                datapurchase();
            }
        );
    </script>
<!-- Akhir mengisi data pada tabel -->

<!-- Ketika tombol submit di form ditekan -->
<script>

        // definisikan tipe method yang berbeda 
        // untuk update=>put (pembedanga adalah inner html pada labelmodalubah berisi Ubah Data Coa)
        // sedangkan untuk input=>post nilai inner html pada labelmodalubah berisi Tambah Data Coa

        
        $(document).ready(function()
            {   		
                $('.formubahpurchase').submit(function(e)
                    {
                        e.preventDefault();
                            $.ajax(
                                {
                                    type: "post", //isinya post untuk insert dan put untuk delete
                                    url: $(this).attr('action'),
                                    data: $(this).serialize(),
                                    dataType: "json",
                                    success: function (response){
                                        // console.log('kssss');
                                        // jika responsenya adalah error
                                        if (response.status == 400) {
                                            if(response.errors.kode_bahanbaku){
                                                $('#kode_bahanbaku').removeClass('is-valid').addClass('is-invalid');
                                                $('.errorkode_bahanbaku').html(response.errors.kode_bahanbaku);
                                            }else{
                                                $('#kode_bahanbaku').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errorkode_bahanbaku').html();
                                            }

                                            if(response.errors.tanggal_beli){
                                                $('#tanggal_beli').removeClass('is-valid').addClass('is-invalid');
                                                $('.errortanggal_beli').html(response.errors.tanggal_beli);
                                            }else{
                                                $('#tanggal_beli').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errortanggal_beli').html();
                                            }

                                            if(response.errors.nama_bahanbaku){
                                                $('#nama_bahanbaku').removeClass('is-valid').addClass('is-invalid');
                                                $('.errornama_bahanbaku').html(response.errors.nama_bahanbaku);
                                            }else{
                                                $('#nama_bahanbaku').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errornama_bahanbaku').html();
                                            }

                                            if(response.errors.nama_perusahaan){
                                                $('#nama_perusahaan').removeClass('is-valid').addClass('is-invalid');
                                                $('.errornama_perusahaan').html(response.errors.nama_perusahaan);
                                            }else{
                                                $('#nama_perusahaan').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errornama_perusahaan').html();
                                            }

                                            if(response.errors.harga){
                                                $('#harga').removeClass('is-valid').addClass('is-invalid');
                                                $('.errorharga').html(response.errors.harga);
                                            }else{
                                                $('#harga').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errorharga').html();
                                            }

                                            if(response.errors.quantity){
                                                $('#quantity').removeClass('is-valid').addClass('is-invalid');
                                                $('.errorquantity').html(response.errors.quantity);
                                            }else{
                                                $('#quantity').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errorquantity').html();
                                            }

                                            if(response.errors.total){
                                                $('#total').removeClass('is-valid').addClass('is-invalid');
                                                $('.errortotal').html(response.errors.total);
                                            }else{
                                                $('#total').removeClass('is-invalid').addClass('is-valid');;
                                                $('.errortotal').html();
                                            }

                                        }
                                        else{
                                            // munculkan pesan sukses
                                            Swal.fire({
                                                title: 'Berhasil!',
                                                text: response.sukses,
                                                icon: 'success',
                                                confirmButtonText: 'Ok'
                                            });
                                            
                                            // kosongkan form
                                            $('#ubahModal').modal('hide');

                                            
                                            datapurchase(); //refresh data coa
                                            

                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError){
                                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                                    } 
                                } 
                            );
                            return false;
                    }
                );
            }
        );
</script>
<!-- Akhir ketika tombol submit di form ditekan -->

@endsection