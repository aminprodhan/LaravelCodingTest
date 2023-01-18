@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach ($variants as $variant)
                            <option value="{{$variant->id}}">{{$variant->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$product->title}} <br> Created at : {{$product->created_at}}</td>
                                <td style="max-width: 200px;">{{$product->description}}</td>
                                <td>

                                    @foreach ($product->getVariantsPrice as $v_product)
                                        <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant-{{$v_product->id}}">
                                            <dt class="col-sm-3 pb-0">
                                                {{$v_product->getVariantOne->variant ?? ''}},
                                                {{$v_product->getVariantTwo->variant ?? ''}},
                                                {{$v_product->getVariantThree->variant ?? ''}}
                                            </dt>
                                            <dd class="col-sm-9">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4 pb-0">Price : {{ number_format($v_product->price,2) }}</dt>
                                                    <dd class="col-sm-8 pb-0">InStock : {{ number_format($v_product->stock,2) }}</dd>
                                                </dl>
                                            </dd>
                                        </dl>
                                    @endforeach

                                    <button
                                        onclick="$('#variant-{{$v_product->id}}').toggleClass('h-auto')"
                                        class="btn btn-sm btn-link">Show more</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$products->links("pagination::bootstrap-4")}}
            </div>
        </div>
        <?php
            $pageInfo=$products->toArray();
            $current_page=$pageInfo['current_page'];
            $per_page=$pageInfo['per_page'];

            //$start_from=
        ?>
        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{$pageInfo['from']}} to {{$pageInfo['to']}} out of {{$pageInfo['total']}}</p>
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </div>
    </div>

@endsection
