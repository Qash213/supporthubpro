@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="https://cdn.muicss.com/mui-0.9.34/css/mui.min.css">
    <link rel="stylesheet" href="https://rawgit.com/alertifyjs/alertify.js/master/dist/css/alertify.css"> --}}
    <style>
        body,
        html {
            margin: 0;
            height: 100%;
        }

        .node-editor {
            overflow: visible !important;
            width: 100% !important;
            height: 730px !important;
        }
        .offcanvas button.btn-close::before {
                content: "\ea00";
               font-family: 'feather' !important;
            }

        .note {
            background: #7ca2ba;
            color: white;
            width: 100%;
            text-align: center;
            padding: 0.5em;
            font-family: sans-serif;
            z-index: 1;
        }

        .note a {
            color: #eee;
            text-decoration: underline;
        }

        .control input,
        .input-control input {
            width: 100%;
            border-radius: 30px;
            background-color: white;
            padding: 2px 6px;
            border: 1px solid #999;
            font-size: 110%;
            width: 170px;
        }

        .content {
            display: flex;
            height: 100%;
        }

        #editor-wrapper {
            flex: 2;
        }

        #editor .socket.string {
            background: #6f377e;
        }

        #editor .socket.action {
            background: white;
            border-color: grey;
            border-radius: 3px;
            width: 15px;
        }

        #editor .node.message-event {
            background: #767676;
        }

        #editor .input-control input {
            width: 140px;
        }

        #telegram {
            margin: 15px;
            flex: 1;
            height: calc(100% - 2 * #{$margin});
            border: 2px solid blue;
            background: url('http://78.media.tumblr.com/913fc95846350c30232a5608a322b78e/tumblr_obykzyjxZt1vbllj8o4_1280.png');
            background-size: cover;
        }

        #telegram .messages {
            height: calc(100% - 68px);
            max-height: calc(100% - 68px);
            overflow: auto;
        }

        #telegram .messages .message {
            display: flex;
            padding: 10px;
        }

        #telegram .messages .message.owner {
            flex-direction: row-reverse;
        }

        #telegram .messages .message .avatar {
            display: inline-block;
            border-radius: 100%;
            flex-grow: 0;
            flex-shrink: 0;
            height: 40px;
            margin: 0 12px;
            overflow: hidden;
        }

        #telegram .messages .message .avatar img {
            height: 40px;
            width: 40px;
        }

        #telegram .messages .message .text-wrap {
            display: inline-block;
        }

        #telegram .messages .message .text-wrap .text {
            padding: 5px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
        }

        #telegram .messages .message .text-wrap .text a {
            color: #4f6;
        }

        #telegram .form {
            background: #839aed;
            height: 80px;
        }

        #editor {
            background-color: #ffffff;
            opacity: 1;
            background-image: linear-gradient(#f1f1f1 3.2px, transparent 3.2px),
                linear-gradient(90deg, #f1f1f1 3.2px, transparent 3.2px),
                linear-gradient(#f1f1f1 1.6px, transparent 1.6px),
                linear-gradient(90deg, #f1f1f1 1.6px, #e8ecff 1.6px);
            background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
            background-position: -3.2px -3.2px, -3.2px -3.2px, -1.6px -1.6px,
                -1.6px -1.6px;
        }
    </style>
@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block justify-content-between">
        <div class="page-leftheader">
            <h4 class="page-title">
                <div class="d-flex">
                    <div class="font-weight-normal text-muted fs-22 border-0 responseFlowName title-responseflow" contenteditable="true">
                        @if($flow)
                            @if($flow->responseName)
                                {{$flow->responseName}}
                                @else
                                {{ lang('Response Flow') }}
                            @endif
                        @else
                        {{ lang('Response Flow') }}
                        @endif
                    </div>
                    {{-- {!! json_encode($flow ? $flow->liveChatFlow : null) !!} --}}
                    <i class="feather feather-edit text-primary ms-2 mt-2 fs-22"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title=""
                        data-bs-original-title="Edit"
                        aria-label="Edit"
                    ></i>
                </div>
            </h4>
        </div>
        <div>
        </div>
        <div class="d-flex">
            <div class="dimmer me-3 d-flex autoSavedSpiner"> <div class="spinner4 my-3 d-none"> <div class="bounce1"></div> <div class="bounce2"></div> <div class="bounce3"></div> </div><b class="ms-3 my-auto d-none text-success">{{ lang('Auto Saved') }}</b></div>
            @if($flowChatId == "null")
                <button class="btn btn-gray tryItOutBtn" href="javascript:void(0);"
                NAME="My Window Name"  title=" My title here "
                onClick='window.open("test-it-out/{{ $flowChatId }}","Ratting","width=850,height=700,0,status=0,scrollbars=1");' disabled="true">{{ lang('Try It Out') }}</button>
                <button class="ms-3 btn btn-md btn-primary" id="liveChatFlowBtn" disabled="true">{{ lang('Active') }}</button>
            @else
                <button class="btn btn-gray tryItOutBtn" href="javascript:void(0);"
                NAME="My Window Name"  title=" My title here "
                onClick='window.open("test-it-out/{{ $flowChatId }}","Ratting","width=850,height=700,0,status=0,scrollbars=1");'>{{ lang('Try It Out') }}</button>
                <button class="ms-3 btn btn-md btn-primary" id="liveChatFlowBtn">{{ lang('Active') }}</button>
            @endif
        </div>

    </div>

    <div class="content">
        <div id="editor-wrapper">
            <div id="editor" class="node-editor editor-wrapper-nodes"></div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
              <h5 id="offcanvasRightLabel">{{ lang('Conversation Editor') }}</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">{{ lang('Edit text') }}</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="10"></textarea>
                    <button class="btn btn-primary mt-2 float-endn conversationEditorBtn" data-bs-dismiss="offcanvas" aria-label="Close">{{ lang('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!--End Page header-->
@endsection

@section('scripts')
    <script src="{{ asset('build/assets/plugins/livechatflow/rete.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/lodash.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/alight.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/vue.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/vue-render-plugin.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/connection-plugin.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/context-menu-plugin.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/task-plugin.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/mui.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/alertify.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/livechatflow/area-plugin.min.js') }}"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>

    <script type="text/javascript">
        var botAvatar = "https://robohash.org/liberovelitdolores.bmp?size=50x50&set=set1";
        var userAvatar = "http://icons.iconarchive.com/icons/visualpharm/must-have/256/User-icon.png";


        var onMessageTask = [];

        var actSocket = new Rete.Socket("Action");
        var strSocket = new Rete.Socket("String");

        const JsRenderPlugin = {
            install(editor, params = {}) {
                editor.on("rendercontrol", ({
                    el,
                    control
                }) => {
                    if (control.render && control.render !== "js") return;

                    control.handler(el, editor);
                });
            }
        };

        class InputControl extends Rete.Control {
            constructor(key) {
                super(key);
                this.render = "js";
                this.key = key;
            }

            handler(el, editor) {
                var input = document.createElement("input");
                el.appendChild(input);

                var text = this.getData(this.key) || "Some message..";

                input.value = text;
                this.putData(this.key, text);
                input.addEventListener("change", () => {
                    this.putData(this.key, input.value);
                });
            }
        }

        class MessageComponent extends Rete.Component {
            constructor() {
                super("Message");
                this.task = {
                    outputs: {
                        text: "output"
                    }
                };
            }

            builder(node) {
                // var inp1 = new Rete.Input("act", "Action", actSocket);
                var out = new Rete.Output("text", "Text", strSocket);
                var out1 = new Rete.Output("act", "Action", actSocket);
                var ctrl = new InputControl("text");

                return node.addControl(ctrl).addOutput(out).addOutput(out1);
            }

            worker(node, inputs,outputs) {
                return {
                    text: node.data.text
                };
            }
        }

        class WelComeMessageComponent extends Rete.Component {
            constructor() {
                super("Welcome Message");
                this.task = {
                    outputs: {
                        text: "output"
                    }
                };
            }

            builder(node) {
                // var out = new Rete.Output("text", "Text", strSocket);
                var out1 = new Rete.Output("act", "Action", actSocket);
                var ctrl = new InputControl("text");

                return node
                    .addControl(ctrl)
                    .addOutput(out1)
            }

            worker(node, inputs) {
                return {
                    text: node.data.text
                };
            }
        }

        class OptionComponent extends Rete.Component {
            constructor() {
                super("Option");
                this.task = {
                    outputs: {
                        act: "option",
                    },
                    init(task){
                        onMessageTask.push(task);
                    }
                };
            }

            builder(node) {
                var inp1 = new Rete.Input("act", "Action", actSocket);
                var ctrl = new InputControl("optionName");
                var inp2 = new Rete.Input("text", "Text", strSocket);


                node.addControl(ctrl).addInput(inp1).addInput(inp2);
            }
            worker(node, inputs, outputs) {
                var text = inputs["text"] ? inputs["text"][0] : node.data.text;
            }
        }

        var components = [
            // new MessageSendComponent(),
            new MessageComponent(),
            new WelComeMessageComponent(),
            new OptionComponent()
        ];

        var container = document.getElementById("editor");
        var editor = new Rete.NodeEditor("demo@0.1.0", container);
        editor.use(VueRenderPlugin.default);
        editor.use(ConnectionPlugin.default);
        editor.use(ContextMenuPlugin.default);
        editor.use(JsRenderPlugin);
        editor.use(TaskPlugin);

        var engine = new Rete.Engine("demo@0.1.0");

        components.map(c => {
            editor.register(c);
            engine.register(c);
        });

        // To add the Created Chat Id
        let autosaveId = "null"

        editor
            .fromJSON(
                {!! json_encode($flow ? $flow->liveChatFlow : null) !!} ? JSON.parse({!! json_encode($flow ? $flow->liveChatFlow : null) !!}) :
                {
                    "id": "demo@0.1.0",
                    "nodes": {
                        "1": {
                            "id": 1,
                            "data": {
                                "text": "Welcome to the Chatbot! How can I assist you today?"
                            },
                            "inputs": {},
                            "outputs": {
                                "act": {
                                    "connections": [{
                                        "node": 2,
                                        "input": "act",
                                        "data": {}
                                    }]
                                }
                            },
                            "position": [-333, -586],
                            "name": "Welcome Message"
                        },
                        "2": {
                            "id": 2,
                            "data": {
                                "optionName": "Sales"
                            },
                            "inputs": {
                                "act": {
                                    "connections": [{
                                        "node": 1,
                                        "output": "act",
                                        "data": {}
                                    }]
                                },
                                "text": {
                                    "connections": [{
                                        "node": 3,
                                        "output": "text",
                                        "data": {}
                                    }]
                                }
                            },
                            "outputs": {},
                            "position": [296, -545],
                            "name": "Option"
                        },
                        "3": {
                            "id": 3,
                            "data": {
                                "text": "Hello World"
                            },
                            "inputs": {},
                            "outputs": {
                                "text": {
                                    "connections": [{
                                        "node": 2,
                                        "input": "text",
                                        "data": {}
                                    }]
                                },
                                "act": {
                                    "connections": []
                                }
                            },
                            "position": [-8, -366],
                            "name": "Message"
                        }
                    }
                }
            )
            .then(() => {

                editor.on("error", err => {
                    alertify.error(err.message);
                });

                editor.on("process connectioncreated connectionremoved nodecreated", async function(data) {
                        if (engine.silent) return;
                        onMessageTask = [];
                        await engine.abort();
                        await engine.process(editor.toJSON());
                    }
                );
                // editor.on("nodecreate", async function(data) {
                //     if(data.name == "Welcome Message"){
                //         setTimeout(() => {
                //             editor.removeNode(editor.nodes[data.id-1])
                //         }, 1000);

                //     }
                // }
                // )
                editor.on('connectionpick', async function(data) {
                        // when changing options remove the focus
                        let ele = document.querySelector('.node.welcome-message').querySelector('input');
                        ele.focus();
                        ele.blur()
                })
                let seletedPopup = true
                editor.on('selectnode',async function(data){
                    setTimeout(() => {
                        if(seletedPopup){
                            new bootstrap.Offcanvas(document.querySelector("#offcanvasRight")).show()
                            if(data.node.name != "Option"){
                                document.querySelector("#offcanvasRight textarea").value = data.node.data.text
                                document.querySelector("#offcanvasRight .conversationEditorBtn").onclick = ()=>{
                                    data.node.data.text = document.querySelector("#offcanvasRight textarea").value
                                    data.e.target.closest('.node').querySelector("input").value = document.querySelector("#offcanvasRight textarea").value
                                }
                            }else{
                                document.querySelector("#offcanvasRight textarea").value = data.node.data.optionName
                                document.querySelector("#offcanvasRight .conversationEditorBtn").onclick = ()=>{
                                    data.node.data.optionName = document.querySelector("#offcanvasRight textarea").value
                                    data.e.target.closest('.node').querySelector("input").value = document.querySelector("#offcanvasRight textarea").value
                                }
                            }
                        }
                    }, 200);
                })
                editor.on('nodetranslate',async function(data){
                    seletedPopup = false
                    setTimeout(() => {
                        seletedPopup = true
                    }, 200);
                })
                // To remove the contextmenu From the WelCome Message
                editor.nodes.forEach((node)=>{
                    if(node.name == "Welcome Message"){
                        node.vueContext.$el.addEventListener('contextmenu',(event)=>{
                            event.preventDefault();
                            event.stopPropagation();
                        })
                    }
                })
                editor.on('contextmenu',async function(data){
                    setTimeout(() => {
                        const items = document.querySelectorAll('.context-menu .item');
                        let welcomeMessageDiv = null;
                        items.forEach(item => {
                            if (item.textContent.trim() === 'Welcome Message') {
                                welcomeMessageDiv = item;
                            }
                        });
                        welcomeMessageDiv?.classList.add("d-none")
                    }, 10);
                    seletedPopup = false
                    setTimeout(() => {
                        seletedPopup = true
                    }, 200);
                })

                editor.on('connectioncreated', function(data) {
                    const outputNode = data.output.node;
                    const inputNode = data.input.node;

                    // Iterate through all nodes to find potential conflicting connections
                    for (const node of editor.nodes) {
                        if (node.name === "Option") {
                            const actionConnections = node.inputs.get('act').connections;
                            const textConnections = node.inputs.get('text').connections;

                            // Check if the same message node is connected via both action and text sockets
                            const actionConnectedNodes = actionConnections.map(c => c.output.node.id);
                            const textConnectedNodes = textConnections.map(c => c.output.node.id);

                            const commonConnectedNodes = actionConnectedNodes.filter(id => textConnectedNodes.includes(id));

                            if (commonConnectedNodes.length > 0) {
                                editor.removeConnection(data);
                                swal({
                                    title: `{{lang('Cannot connect', 'alerts')}}`,
                                    text: "{{lang('Cannot connect Option node to the same Message node via both Action and Text sockets.', 'alerts')}}",
                                    icon: "error",
                                })
                                break;
                            }
                        }
                    }
                });

                let denouncing
                editor.on('connectioncreated connectionremoved nodecreated keyup', async function(data){

                    // For the Auto Save
                    clearTimeout(denouncing)

                    if({!! json_encode($flowChatId) !!} == 'null' && autosaveId == 'null'){
                        document.querySelector("#liveChatFlowBtn").disabled = true
                    }

                    denouncing = setTimeout(()=>{
                        let AutoSaveData = {
                            chatId : {!! json_encode($flowChatId) !!} != 'null' ? {!! json_encode($flowChatId) !!} : autosaveId,
                            chat : JSON.stringify(editor.toJSON()),
                            responseName : document.querySelector('.responseFlowName').innerText
                        }

                        document.querySelector(".autoSavedSpiner .spinner4").classList.remove("d-none")
                        document.querySelector(".autoSavedSpiner b").classList.add("d-none")
                        fetch('{{route('admin.liveChatFlowAutoSave')}}',{
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            method: "POST",
                            body: JSON.stringify(AutoSaveData)
                        })
                        .then(function(response) {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Network response was not ok');
                            }
                        })
                        .then(function(data) {
                            if (data.success) {
                                document.querySelector(".autoSavedSpiner .spinner4").classList.add("d-none")
                                document.querySelector(".autoSavedSpiner b").classList.remove("d-none")
                                if(autosaveId == "null"){
                                    autosaveId = data.success.id
                                    document.querySelector('.tryItOutBtn').setAttribute("onclick",document.querySelector(".tryItOutBtn").getAttribute('onclick').replace('null',data.success.id))
                                    document.querySelector('.tryItOutBtn').removeAttribute("disabled")
                                }
                                document.querySelector("#liveChatFlowBtn").disabled = false
                            }
                        })
                        .catch(function(error) {
                            console.error('Fetch error:', error);
                        });
                    },5000)
                })

                editor.trigger("process");
                editor.view.resize();
                AreaPlugin.zoomAt(editor);
            });

            // for the chat Active
        document.querySelector("#liveChatFlowBtn").onclick = ()=>{
            swal({
                title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                text: "{{lang('This Flow will maked was default', 'alerts')}}",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((res)=>{
                    if(res){
                        let SaveData = {
                                        chatId : {!! json_encode($flowChatId) !!} != 'null' ? {!! json_encode($flowChatId) !!} : autosaveId,
                                        chat : JSON.stringify(editor.toJSON())
                                    }
                        fetch('{{route('admin.liveChatFlowSave')}}',{
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            method: "POST",
                            body: JSON.stringify(SaveData)
                        })
                        .then(function(response) {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Network response was not ok');
                            }
                        })
                        .then(function(data) {
                            if (data.success) {
                                toastr.success(data.success);
                                document.querySelector(".autoSavedSpiner .spinner4").classList.add("d-none")
                                document.querySelector(".autoSavedSpiner b").classList.remove("d-none")
                                if(autosaveId == "null"){
                                    autosaveId = data.flow.id
                                }
                                document.querySelector("#liveChatFlowBtn").disabled = false
                            }
                        })
                        .catch(function(error) {
                            console.error('Fetch error:', error);
                        });
                    }
                })
        }

        // Remove the localStorage when usr click the try it out
        document.querySelector(".tryItOutBtn").addEventListener("click",()=>{
                localStorage.removeItem("LiveChatCust")
        })
    </script>
@endsection
