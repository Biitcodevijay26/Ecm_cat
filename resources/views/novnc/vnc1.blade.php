@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="{{ url('novnc/app/styles/base.css') }}">
<link rel="stylesheet" href="{{ url('novnc/app/styles/input.css') }}">
@endsection
@section('content')
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header d-none">
                <div>
                    <h1 class="page-title">{{$title}}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add Company
                    </a> --}}
                </div>
            </div>
            <!-- PAGE-HEADER END -->

             <!-- ROW-1 OPEN -->
             <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}} Info</h3>
                        </div>
                        <div class="card-body">
                            <div id="noVNC_fallback_error" class="noVNC_center">
                                <div>
                                    <div>noVNC encountered an error:</div>
                                    <br>
                                    <div id="noVNC_fallback_errormsg"></div>
                                </div>
                            </div>
                        
                            <!-- noVNC Control Bar -->
                            <div id="noVNC_control_bar_anchor" class="noVNC_vcenter">
                        
                                <div id="noVNC_control_bar">
                                    <div id="noVNC_control_bar_handle" title="Hide/Show the control bar"><div></div></div>
                        
                                    <div class="noVNC_scroll">
                        
                                    {{-- <h1 class="noVNC_logo" translate="no"><span>no</span><br>VNC</h1> --}}
                        
                                    <hr>
                        
                                    <!-- Drag/Pan the viewport -->
                                    <input type="image" alt="Drag" src="{{ url('novnc/app/images/drag.svg') }}"
                                        id="noVNC_view_drag_button" class="noVNC_button noVNC_hidden"
                                        title="Move/Drag Viewport">
                        
                                    <!--noVNC Touch Device only buttons-->
                                    <div id="noVNC_mobile_buttons"> 
                                        <input type="image" alt="Keyboard" src="{{ url('novnc/app/images/keyboard.svg') }}"
                                            id="noVNC_keyboard_button" class="noVNC_button" title="Show Keyboard">
                                    </div>
                        
                                    <!-- Extra manual keys -->
                                    <input type="image" alt="Extra keys" src="{{ url('novnc/app/images/toggleextrakeys.svg') }}"
                                        id="noVNC_toggle_extra_keys_button" class="noVNC_button"
                                        title="Show Extra Keys">
                                    <div class="noVNC_vcenter">
                                    <div id="noVNC_modifiers" class="noVNC_panel">
                                        <input type="image" alt="Ctrl" src="{{ url('novnc/app/images/ctrl.svg') }}"
                                            id="noVNC_toggle_ctrl_button" class="noVNC_button"
                                            title="Toggle Ctrl">
                                        <input type="image" alt="Alt" src="{{ url('novnc/app/images/alt.svg') }}"
                                            id="noVNC_toggle_alt_button" class="noVNC_button"
                                            title="Toggle Alt">
                                        <input type="image" alt="Windows" src="{{ url('novnc/app/images/windows.svg') }}"
                                            id="noVNC_toggle_windows_button" class="noVNC_button"
                                            title="Toggle Windows">
                                        <input type="image" alt="Tab" src="{{ url('novnc/app/images/tab.svg') }}"
                                            id="noVNC_send_tab_button" class="noVNC_button"
                                            title="Send Tab">
                                        <input type="image" alt="Esc" src="{{ url('novnc/app/images/esc.svg') }}"
                                            id="noVNC_send_esc_button" class="noVNC_button"
                                            title="Send Escape">
                                        <input type="image" alt="Ctrl+Alt+Del" src="{{ url('novnc/app/images/ctrlaltdel.svg') }}"
                                            id="noVNC_send_ctrl_alt_del_button" class="noVNC_button"
                                            title="Send Ctrl-Alt-Del">
                                    </div>
                                    </div>
                        
                                    <!-- Shutdown/Reboot -->
                                    <input type="image" alt="Shutdown/Reboot" src="{{ url('novnc/app/images/power.svg') }}"
                                        id="noVNC_power_button" class="noVNC_button"
                                        title="Shutdown/Reboot...">
                                    <div class="noVNC_vcenter">
                                    <div id="noVNC_power" class="noVNC_panel">
                                        <div class="noVNC_heading">
                                            <img alt="" src="{{ url('novnc/app/images/power.svg') }}"> Power
                                        </div>
                                        <input type="button" id="noVNC_shutdown_button" value="Shutdown">
                                        <input type="button" id="noVNC_reboot_button" value="Reboot">
                                        <input type="button" id="noVNC_reset_button" value="Reset">
                                    </div>
                                    </div>
                        
                                    <!-- Clipboard -->
                                    <input type="image" alt="Clipboard" src="{{ url('novnc/app/images/clipboard.svg') }}"
                                        id="noVNC_clipboard_button" class="noVNC_button"
                                        title="Clipboard">
                                    <div class="noVNC_vcenter">
                                    <div id="noVNC_clipboard" class="noVNC_panel">
                                        <div class="noVNC_heading">
                                            <img alt="" src="{{ url('novnc/app/images/clipboard.svg') }}"> Clipboard
                                        </div>
                                        <p class="noVNC_subheading">
                                            Edit clipboard content in the textarea below.
                                        </p>
                                        <textarea id="noVNC_clipboard_text" rows=5></textarea>
                                    </div>
                                    </div>
                        
                                    <!-- Toggle fullscreen -->
                                    <input type="image" alt="Full Screen" src="{{ url('novnc/app/images/fullscreen.svg') }}"
                                        id="noVNC_fullscreen_button" class="noVNC_button noVNC_hidden"
                                        title="Full Screen">
                        
                                    <!-- Settings -->
                                    <input type="image" alt="Settings" src="{{ url('novnc/app/images/settings.svg') }}"
                                        id="noVNC_settings_button" class="noVNC_button"
                                        title="Settings">
                                    <div class="noVNC_vcenter">
                                    <div id="noVNC_settings" class="noVNC_panel">
                                        <div class="noVNC_heading">
                                            <img alt="" src="{{ url('novnc/app/images/settings.svg') }}"> Settings
                                        </div>
                                        <ul>
                                            <li>
                                                <label><input id="noVNC_setting_shared" type="checkbox"> Shared Mode</label>
                                            </li>
                                            <li>
                                                <label><input id="noVNC_setting_view_only" type="checkbox"> View Only</label>
                                            </li>
                                            <li><hr></li>
                                            <li>
                                                <label><input id="noVNC_setting_view_clip" type="checkbox"> Clip to Window</label>
                                            </li>
                                            <li>
                                                <label for="noVNC_setting_resize">Scaling Mode:</label>
                                                <select id="noVNC_setting_resize" name="vncResize">
                                                    <option value="off">None</option>
                                                    <option value="scale">Local Scaling</option>
                                                    <option value="remote">Remote Resizing</option>
                                                </select>
                                            </li>
                                            <li><hr></li>
                                            <li>
                                                <div class="noVNC_expander">Advanced</div>
                                                <div><ul>
                                                    <li>
                                                        <label for="noVNC_setting_quality">Quality:</label>
                                                        <input id="noVNC_setting_quality" type="range" min="0" max="9" value="6">
                                                    </li>
                                                    <li>
                                                        <label for="noVNC_setting_compression">Compression level:</label>
                                                        <input id="noVNC_setting_compression" type="range" min="0" max="9" value="2">
                                                    </li>
                                                    <li><hr></li>
                                                    <li>
                                                        <label for="noVNC_setting_repeaterID">Repeater ID:</label>
                                                        <input id="noVNC_setting_repeaterID" type="text" value="">
                                                    </li>
                                                    <li>
                                                        <div class="noVNC_expander">WebSocket</div>
                                                        <div><ul>
                                                            <li>
                                                                <label><input id="noVNC_setting_encrypt" type="checkbox"> Encrypt</label>
                                                            </li>
                                                            <li>
                                                                <label for="noVNC_setting_host">Host:</label>
                                                                <input id="noVNC_setting_host" value="3.108.245.1568">
                                                            </li>
                                                            <li>
                                                                <label for="noVNC_setting_port">Port:</label>
                                                                <input id="noVNC_setting_port" type="number" value="6801">
                                                            </li>
                                                            <li>
                                                                <label for="noVNC_setting_path">Path:</label>
                                                                <input id="noVNC_setting_path" type="text" value="websockify">
                                                            </li>
                                                        </ul></div>
                                                    </li>
                                                    <li><hr></li>
                                                    <li>
                                                        <label><input id="noVNC_setting_reconnect" type="checkbox"> Automatic Reconnect</label>
                                                    </li>
                                                    <li>
                                                        <label for="noVNC_setting_reconnect_delay">Reconnect Delay (ms):</label>
                                                        <input id="noVNC_setting_reconnect_delay" type="number">
                                                    </li>
                                                    <li><hr></li>
                                                    <li>
                                                        <label><input id="noVNC_setting_show_dot" type="checkbox"> Show Dot when No Cursor</label>
                                                    </li>
                                                    <li><hr></li>
                                                    <!-- Logging selection dropdown -->
                                                    <li>
                                                        <label>Logging:
                                                            <select id="noVNC_setting_logging" name="vncLogging">
                                                            </select>
                                                        </label>
                                                    </li>
                                                </ul></div>
                                            </li>
                                            <li class="noVNC_version_separator"><hr></li>
                                            <li class="noVNC_version_wrapper">
                                                <span>Version:</span>
                                                <span class="noVNC_version"></span>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                        
                                    <!-- Connection Controls -->
                                    <input type="image" alt="Disconnect" src="{{ url('novnc/app/images/disconnect.svg') }}"
                                        id="noVNC_disconnect_button" class="noVNC_button"
                                        title="Disconnect">
                        
                                    </div>
                                </div>
                        
                            </div> <!-- End of noVNC_control_bar -->
                        
                            <div id="noVNC_hint_anchor" class="noVNC_vcenter">
                                <div id="noVNC_control_bar_hint">
                                </div>
                            </div>
                        
                            <!-- Status Dialog -->
                            <div id="noVNC_status"></div>
                        
                            <!-- Connect button -->
                            <div class="noVNC_center">
                                <div id="noVNC_connect_dlg">
                                    {{-- <p class="noVNC_logo" translate="no"><span>no</span>VNC</p> --}}
                                    <div>
                                        <button id="noVNC_connect_button">
                                            <img alt="" src="{{ url('novnc/app/images/connect.svg') }}"> Connect
                                        </button>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Server Key Verification Dialog -->
                            <div class="noVNC_center noVNC_connect_layer">
                            <div id="noVNC_verify_server_dlg" class="noVNC_panel"><form>
                                <div class="noVNC_heading">
                                    Server identity
                                </div>
                                <div>
                                    The server has provided the following identifying information:
                                </div>
                                <div id="noVNC_fingerprint_block">
                                    <b>Fingerprint:</b>
                                    <span id="noVNC_fingerprint"></span>
                                </div>
                                <div>
                                    Please verify that the information is correct and press
                                    "Approve". Otherwise press "Reject".
                                </div>
                                <div>
                                    <input id="noVNC_approve_server_button" type="submit" value="Approve" class="noVNC_submit">
                                    <input id="noVNC_reject_server_button" type="button" value="Reject" class="noVNC_submit">
                                </div>
                            </form></div>
                            </div>
                        
                            <!-- Password Dialog -->
                            <div class="noVNC_center noVNC_connect_layer">
                            <div id="noVNC_credentials_dlg" class="noVNC_panel"><form>
                                <div class="noVNC_heading">
                                    Credentials
                                </div>
                                <div id="noVNC_username_block">
                                    <label for="noVNC_username_input">Username:</label>
                                    <input id="noVNC_username_input">
                                </div>
                                <div id="noVNC_password_block">
                                    <label for="noVNC_password_input">Password:</label>
                                    <input id="noVNC_password_input" type="password">
                                </div>
                                <div>
                                    <input id="noVNC_credentials_button" type="submit" value="Send Credentials" class="noVNC_submit">
                                </div>
                            </form></div>
                            </div>
                        
                            <!-- Transition Screens -->
                            <div id="noVNC_transition">
                                <div id="noVNC_transition_text"></div>
                                <div>
                                <input type="button" id="noVNC_cancel_reconnect_button" value="Cancel" class="noVNC_submit">
                                </div>
                                <div class="noVNC_spinner"></div>
                            </div>
                        
                            <!-- This is where the RFB elements will attach -->
                            <div id="noVNC_container" style="max-width: 100%;">
                                <!-- Note that Google Chrome on Android doesn't respect any of these,
                                     html attributes which attempt to disable text suggestions on the
                                     on-screen keyboard. Let's hope Chrome implements the ime-mode
                                     style for example -->
                                <textarea id="noVNC_keyboardinput" autocapitalize="off"
                                    autocomplete="off" spellcheck="false" tabindex="-1"></textarea>
                            </div>
                        
                            <audio id="noVNC_bell">
                                <source src="{{ url('novnc/app/sounds/bell.oga') }}" type="audio/ogg">
                                <source src="{{ url('novnc/app/sounds/bell.mp3') }}" type="audio/mpeg">
                            </audio>
                        </div>
                        <div class="card-footer text-end">
                            <input id="new_port" type="hidden" value="{{$port}}">
                            <input id="new_host" type="hidden" value="{{$server_ip}}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 CLOSED -->

        </div>
         <!-- CONTAINER END -->
    </div>
</div>
@endsection
@section('page_level_js')
<script type="module" crossorigin="anonymous" src="{{ url('novnc/app/error-handler.js') }}"></script>
<script type="module" crossorigin="anonymous" src="{{ url('novnc/app/ui.js') }}"></script>
<script>
$(document).ready(function () {
    $('#noVNC_connect_button').trigger('click');

    $(document).on('keydown', function(e) {
       //console.log('key ',e.which);
    });
});
</script>
@endsection

