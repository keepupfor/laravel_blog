@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-6">
                <h3>标签
                    <small>» 列表</small>
                </h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="/admin/tag/create" class="btn btn-success btn-md">
                    <i class="fa fa-plus-circle"></i> 创建标签
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                @include('admin.partials.errors')
                @include('admin.partials.success')

                <table id="tags-table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>标签</th>
                        <th>标题</th>
                        <th class="hidden-sm">别名</th>
                        <th class="hidden-md">标签图片</th>
                        <th class="hidden-md">介绍</th>
                        <th class="hidden-md">布局</th>
                        <th class="hidden-sm">排序</th>
                        <th data-sortable="false">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->tag }}</td>
                            <td>{{ $tag->title }}</td>
                            <td class="hidden-sm">{{ $tag->subtitle }}</td>
                            <td class="hidden-md">{{ $tag->page_image }}</td>
                            <td class="hidden-md">{{ $tag->meta_description }}</td>
                            <td class="hidden-md">{{ $tag->layout }}</td>
                            <td class="hidden-sm">
                                @if ($tag->reverse_direction)
                                    升序
                                @else
                                    降序
                                @endif
                            </td>
                            <td>
                                <a href="/admin/tag/{{ $tag->id }}/edit" class="btn btn-xs btn-info">
                                    <i class="fa fa-edit"></i> 编辑
                                </a>
                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                        data-target="#modal-delete"
                                        onclick="delete_tag({{ $tag->id }})">
                                    <i class="fa fa-times-circle"></i>
                                    删除
                                </button>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@stop

@section('scripts')
    <script>
        $(function () {
            $("#tags-table").DataTable({});
        });
        function delete_tag(id) {
            $("#delete_form").removeAttr('action');
            $("#delete_form").attr('action', "/admin/tag/" + id);
        }
    </script>
@stop