@extends('layouts.admin.skeleton')
@section('title', __('Add Role'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => __('Add Role'),
        'breadcrumbs' => [
            route('admin.roles.organization') => __('Roles'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.roles.organization',
            ]),
        ]
    ])
@endsection

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jstree.min.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('assets/js/jstree.min.js') }}"></script>
@endsection

@section('content')
    <style>
        [id='1'].jstree-node {
            pointer-events: none;
            opacity: 0.4;
        }
        .jstree-default .jstree-checked {
            color: #008000;
            font-weight: 600;
            background-color: #a6ffa6;
        }
        .custom-switch.cs-small {
            width: 110px;
        }
        .custom-switch-slider:before {
            width: 82px;
        }
        .jstree-default .jstree-search {
            background-color: yellow !important;
        }
        .input-search-icon::-webkit-search-cancel-button {
            -webkit-appearance: searchfield !important;
            display: inline !important;
            appearance: searchfield-cancel-button !important;
        }

        @media (min-width: 1100px) {
        .jstree-default .jstree-node {
            margin-left: 30px;
        }
    }
    </style>
    <form id="saveForm" class="manualSubmission" action="{{ route('admin.roles.organization.save') }}" method="POST" data-parsley-validate>
        @csrf
        <input type="hidden" id="menu_ids" name="menu_ids" value="" />
        <div class="card">
            <div class="card-block">
                <h5 class="text-primary"><i class="ph ph-building"></i> Role Details</h5>
                <hr>
                <div class="row">
                    <div class="col-md-7">
                        <label class="col-form-label">
                            Choose Menus <span class="text-danger">*</span>
                        </label>
                        <label class="custom-switch cs-small align-middle my-0 ml-3 bs-tt" title="Collapse / Expand Menus" data-placement="right">
                            <input id="toggle-menus" type="checkbox" value="1" />
                            <span class="custom-switch-slider" data-off="COLLAPSED" data-on="EXPANDED"></span>
                        </label>
                        <div class="mt-2">
                            <input type="search" class="form-control input-search-icon" placeholder="Search menus" id="MenuSearch" />
                        </div>
                        <div class="border-2 border-danger text-dark empty mt-3 px-2 py-1 rounded" id="jstree-no-results-found"></div>
                        <div class="help-block with-errors mb-3">
                            @error('menu_ids') {{ $message }} @enderror
                        </div>
                        <div id="menu_structure">
                            <ul>
                                <li id="-1" data-jstree='{"opened": true}'>
                                    All Permissions
                                    <ul>
                                        @php
                                            $previousFirstParent = '';
                                            $previousSecondParent = '';
                                            $counter = 0;
                                            $totalMenus = count($menus);
                                        @endphp
                    
                                        @foreach ($menus as $item)
                                            @php
                                                $moduleHeading = $item->is_heading && $item->level == 0 ? 'class="first_parent"' : 'data-jstree="{\"opened\":true}"';
                                                $lastRecord = ($counter + 1) == $totalMenus;
                                            @endphp
                    
                                            @if ($previousFirstParent !== $item->first_parent)
                                                <li id="{{ $item->id }}" {!! $moduleHeading !!}>
                                                    <span class="bs-tt parent-bs-tt" title="Menu Group" data-placement="right">{{ $item->first_parent }}</span>
                                                    <ul>
                                                    @php $previousFirstParent = $item->first_parent; @endphp
                                            @endif
                    
                                            @if ($item->first_parent !== $item->second_parent && $item->second_parent != '')
                                                @if ($previousSecondParent !== $item->second_parent)
                                                    @php
                                                        $customMenuAction = $item->first_parent == 'Quotations' ? 'jt-menu-action' : '';
                                                    @endphp
                                                    <li id="{{ $item->id }}" data-jstree='{"opened":true}'>
                                                        <span class="bs-tt parent-bs-tt {{ $customMenuAction }}" title="Menu" data-placement="right">{{ $item->second_parent }}</span>
                                                        <ul>
                                                    @php $previousSecondParent = $item->second_parent; @endphp
                                                @endif
                    
                                                @if ($item->second_parent !== $item->name && $item->name != '')
                                                    <li id="{{ $item->id }}" data-jstree='{"type":"file"}'>
                                                        <span class="bs-tt parent-bs-tt jt-menu-action" title="Action" data-placement="right">{{ $item->name }}</span>
                                                    </li>
                                                @endif
                    
                                                @if ($lastRecord || $previousSecondParent !== $menus[$counter + 1]->second_parent)
                                                    </ul></li>
                                                @endif
                                            @endif
                    
                                            @if ($lastRecord || $previousFirstParent !== $menus[$counter + 1]->first_parent)
                                                </ul></li>
                                            @endif
                    
                                            @php $counter++; @endphp
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5" id="action-container">
                        <div class="form-group">
                            {{lms_form_label('Role Name')}}
                            <input type="text" class="form-control" id="role_name" name="name" value="{{ old('name')}}" placeholder="{{__('Enter Role Name')}}" @lmsparsley(organization_roles_add,name)>
                            @error('name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{lms_form_label('Description', false)}}
                            <textarea id="description" name="description" class="form-control h-180" rows="4"placeholder="{{__('Enter Description')}}" @lmsparsley(organization_roles_add,description)>{{ old('description')}}</textarea>
                            @error('description')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row fixed-submit-container">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <style>
        
    </style>
    <script>
        $(document).ready(function() {
            $('#action-container').scrollToFixed({
                marginTop: 100,
                //limit: 922
            });

            var DashboardMenuId = 1;
            var Oldmenu_ids = JSON.parse(decode_html_entities('["1"]'));
            //console.log(Oldmenu_ids);
            var menuStructure = $('#menu_structure');
            menuStructure.jstree({
                core: {
                    themes: {
                        responsive: false
                    },
                    check_callback: true,
                },
                types: {
                    default: {
                        icon: 'd-none'
                    },
                    file: {
                        icon: 'd-none'
                    }
                },
                checkbox: {
                    three_state: true, // to avoid that fact that checking a node also check others
                    whole_node: true,  // to avoid checking the box just clicking the node 
                    tie_selection: false // for checking without selecting and selecting without checking
                },
                search: {
                    case_insensitive: true,
                    show_only_matches: true,
                    show_only_matches_children: true
                },
                plugins: ['types', 'checkbox', 'search']
            });

            $('#MenuSearch').keyup(function () {
                $('#jstree-no-results-found').html('');
                menuStructure.jstree(true).show_all();
                menuStructure.jstree('search', this.value);
                $('#toggle-menus')[0].checked = true;
                open_close_menus();
            });

            menuStructure.on('search.jstree', function (nodes, str, res) {
                if (str.nodes.length === 0) {
                    menuStructure.jstree(true).hide_all();
                    $('#jstree-no-results-found').html('<i class="fa fa-info-circle text-danger"></i> No menus found');
                }
            });

            menuStructure.on("check_node.jstree", function (e, data) {
                if (data.node.text.includes('jt-menu-action') && !data.node.text.includes('Listing')) {
                    var tree = menuStructure.jstree(true);
                    var siblings = tree.get_node(data.node.parent).children;
                    for (var item in siblings) {
                        var c_node = tree.get_node(siblings[item]);
                        if (c_node.text.includes('Listing')) {
                            menuStructure.jstree().check_node({ id: siblings[item] });
                            break;
                        }
                    }
                }
            });

            menuStructure.on("uncheck_node.jstree", function (e, data) {
                if (data.node.id == '-1') {
                    setTimeout(() => {
                        menuStructure.jstree().check_node({ id: DashboardMenuId });
                    }, 100);
                }
                else if (data.node.text.includes('jt-menu-action') && data.node.text.includes('Listing')) {
                    unnotify();
                    var tree = menuStructure.jstree(true);
                    var siblings = tree.get_node(data.node.parent).children;
                    var selected = data.selected;
                    var remaining = selected.filter(val => !siblings.includes(val));
                    if (selected.length != remaining.length) {
                        notify({ title: 'Listing action is required because one or more sibling actions enabled', animIn: AppConfig.VALIDATION_INPUT_ERROR_ANIMATION, delay: 5000 });
                        menuStructure.jstree().check_node({ id: data.node.id });
                    }
                }
            });

            menuStructure.on('ready.jstree', function () {
                for (var item in Oldmenu_ids) {
                    menuStructure.jstree().check_node({ id: Oldmenu_ids[item] });
                }
            });

            $('#toggle-menus').on('change', function () {
                open_close_menus();
            });

            function open_close_menus() {
                $('.first_parent').each(function () {
                    if ($('#toggle-menus')[0].checked) {
                        menuStructure.jstree().open_node({ id: this.id });
                    } else {
                        menuStructure.jstree().close_node({ id: this.id });
                    }
                });
            }

            $('#saveForm').on('lms.form.validated', function (e){
                var RoleName = $('#role_name').val() || '';
                var SelectedMenus = $('#menu_structure').jstree("get_checked", null, true).join(',');
                if (RoleName.trim() == '') {
                    notify({ title: 'Please enter role name', animIn: AppConfig.VALIDATION_ERROR_ANIMATION });
                } else if (SelectedMenus == '') {
                    notify({ title: 'Please choose at least one menu', animIn: AppConfig.VALIDATION_ERROR_ANIMATION });
                } else {
                    $('#menu_ids').val(SelectedMenus);
                    show_loader();
                    this.submit();
                }
            });

            $('#MenuSearch').on('input', function () {
                if (this.value == '') {
                    $('#MenuSearch').trigger('keyup');
                }
            });
        });
    </script>
@endsection