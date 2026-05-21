@extends('admin.admin_layouts')
@section('admin_content')
 <style>
    textarea {
  width: 100%;
  height: 80px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}
 </style>  
 <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>Add NDR Action</h2>
                </div>
            </div>
        </div> 
    <form action="{{ route('admin.payment.update') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <!-- <div class="card-header py-3">
                        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                        
                    </div> -->
                    <div class="card-body">
                    <div class="form-group col-md-12" style="padding:0">
                                <label for="closing_description" class="form-control-label">Description</label><span class="required"> *</span>:<br>
                                <textarea name="closing_description" id="textareaID" required></textarea>
                            </div>
                        <button type="submit" class="btn btn-success col-md-4">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
