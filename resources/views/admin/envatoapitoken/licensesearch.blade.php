@extends('layouts.adminmaster')
							@section('content')

                            <!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Envato')}}</span></h4>
								</div>
							</div>
							<!--End Page header-->

                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h4 class="card-title">{{lang('Envato License')}}</h4>
                                    </div>
                                    <div class="card-body pb-6">
                                        <form id="envatolicense_form" name="envatolicense" method="POST"  enctype="multipart/form-data" >
                                            <div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6">
                                                    <input type="search" class="form-control" name="envato_search" >
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2">
                                                    <button type="submit" class="btn btn-success" id="verifybtn">{{lang('Verify License')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="dataenvato"></div>
                                </div>
                            </div>


                            @endsection

        @section('scripts')

        <script type="text/javascript">
            $(function() {
                "use strict";

                // Csrf Field
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('body').on('submit', '#envatolicense_form', function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $('#verifybtn').html(`Loading.. <i class="fa fa-spinner fa-spin"></i>`);
                    $.ajax({
                        type:'POST',
                        url: "{{route('admin.envatolicensesearchget')}}",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            $('#verifybtn').html(`{{lang('Verify License')}}`);
                            $('#dataenvato').html(data);
                        },
                        error: function(data){
                            $('#verifybtn').html(`{{lang('Verify License')}}`);
                        }
                    });

                });
            })
        </script>
        @endsection
