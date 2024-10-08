@extends('layouts.adminmaster')


                            @section('content')

                            <!--Page header-->
                            <div class="page-header d-xl-flex d-block">
                                <div class="page-leftheader">
                                    <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Groups', 'menu')}}</span></h4>
                                </div>
                            </div>
                            <!--End Page header-->

                            <!-- Edit Groups-->
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="card ">
                                    <div class="card-header border-0">
                                        <h4 class="card-title">{{lang('Edit Group')}}</h4>
                                    </div>
                                    <form method="POST" action="{{ url('/admin/groups/update/'.$group->id )}}">
                                        @csrf

                                        @honeypot
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label class="form-label">{{lang('Name')}} <span class="text-red">*</span></label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control @error('groupname') is-invalid @enderror" placeholder="" name="groupname" value="{{$group->groupname}}">
                                                        @error('groupname')

                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ lang($message) }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label class="form-label">{{lang('Select Employees')}} </label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="custom-controls-stacked d-md-flex" >
                                                            <select multiple="multiple" class="form-control select2" data-placeholder="{{lang('Select Agent')}}" name="user_id[]" id="username" >
                                                                @foreach ($users as $item)
                                                                @if($item->id != 1)

                                                                <option value="{{$item->id}}" @if($item->id) @if(in_array($item->id,$grop)) selected @endif @endif>{{$item->name}} @if(!empty($item->getRoleNames()[0])) ({{$item->getRoleNames()[0]}}) @endif</option>
                                                                @endif
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        @error('user_id')

                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ lang('The Employees field is required') }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="form-group float-end">
                                                <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Edit Groups-->

                            @endsection


        @section('scripts')

        <!-- INTERNAL Index js-->
        @vite(['resources/assets/js/select2.js'])

        @endsection
