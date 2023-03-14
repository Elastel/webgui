<?php
require 'includes/csrf.php';
ensureCSRFSessionToken();

require_once 'includes/config.php';
require_once 'includes/defaults.php';
require_once RASPI_CONFIG.'/raspap.php';
require_once 'includes/locale.php';
require_once 'includes/functions.php';
require_once 'includes/dashboard.php';
require_once 'includes/authenticate.php';
require_once 'includes/admin.php';
require_once 'includes/dhcp.php';
require_once 'includes/hostapd.php';
require_once 'includes/adblock.php';
require_once 'includes/system.php';
require_once 'includes/sysstats.php';
require_once 'includes/configure_client.php';
require_once 'includes/networking.php';
require_once 'includes/themes.php';
require_once 'includes/data_usage.php';
require_once 'includes/about.php';
require_once 'includes/openvpn.php';
require_once 'includes/wireguard.php';
require_once 'includes/torproxy.php';
require_once 'includes/basic.php';
require_once 'includes/interfaces.php';
require_once 'includes/modbus.php';
require_once 'includes/s7.php';
require_once 'includes/fx.php';
require_once 'includes/io.php';
require_once 'includes/server.php';
require_once 'includes/ddns.php';
require_once 'includes/bacnet.php';
require_once 'includes/datadisplay.php';
require_once 'includes/detection.php';
require_once 'includes/macchina.php';
require_once 'includes/opcua.php';
require_once 'includes/lorawan.php';

$config = getConfig();
$model = getModel();
$output = $return = 0;
$page = $_SERVER['PATH_INFO'];

$theme_url = getThemeOpt();
$toggleState = getSidebarState();
$bridgedEnabled = getBridgedState();

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php echo CSRFMetaTag() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo _("Elastel Configuration Portal"); ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="dist/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- SB-Admin-2 CSS -->
    <link href="dist/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="dist/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Huebee CSS -->
    <link href="dist/huebee/huebee.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="dist/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- RaspAP Fonts -->
    <link href="dist/raspap/css/style.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="<?php echo $theme_url; ?>" title="main" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="app/icons/favicon.png?ver=2.0">
    <link rel="apple-touch-icon" sizes="180x180" href="app/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="app/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="app/icons/favicon-16x16.png">
    <link rel="icon" type="image/png" href="app/icons/favicon.png" />
    <link rel="manifest" href="app/icons/site.webmanifest">
    <link rel="mask-icon" href="app/icons/safari-pinned-tab.svg" color="#b91d47">
    <meta name="msapplication-config" content="app/icons/browserconfig.xml">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="theme-color" content="#ffffff">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body id="page-top" style="font-family:'Arial','Microsoft YaHei','黑体','宋体',sans-serif">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      <ul class="navbar-nav sidebar sidebar-light d-none d-md-block accordion <?php echo (isset($toggleState)) ? $toggleState : null ; ?>" id="accordionSidebar">
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <div class="row">
          <div class="col-xs ml-3 sidebar-brand-icon">
            <img src="app/img/raspAP-logo.php" class="navbar-logo" width="64" height="64">
          </div>
          <div class="col-xs ml-2 cbi-model"><?php echo _($model);?></div>
        </div>
        <li class="nav-item">
          <a class="nav-link" href="wlan0_info"><i class="fas fa-tachometer-alt fa-fw mr-2"></i><span class="nav-label"><?php echo _("Dashboard"); ?></span></a>
        </li>
        <li class="nav-item" id="page_network">
          <a class="nav-link navbar-toggle collapsed" id="network" href="#" data-toggle="collapse" data-target="#navbar-collapse-network">
              <i class="fas fa-network-wired fa-fw mr-2"></i>
              <span class="nav-label"><?php echo _("Network"); ?></a>
          </a>
          <div class="collapse navbar-collapse" id="navbar-collapse-network">
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item" name="wan" id="wan" ><a class="nav-link" href="network_conf"><?php echo _("WAN"); ?></a></li>
              <li class="nav-item" name="lan" id="lan" ><a class="nav-link" href="dhcpd_conf"><?php echo _("LAN"); ?></a></li>
              <li class="nav-item" name="wifi" id="wifi" ><a class="nav-link" href="hostapd_conf"><?php echo _("WiFi"); ?></a></li>
              <li class="nav-item" name="wifi_client" id="wifi_client" ><a class="nav-link" href="wpa_conf"><?php echo _("WiFi client"); ?></a></li>
              <li class="nav-item" name="online_detection" id="online_detection" ><a class="nav-link" href="detection_conf"><?php echo _("Online Detection"); ?></a></li>
              <?php if ($model == "EG500" || $model == "EG410") : ?>
              <li class="nav-item" name="lorawan" id="lorawan" ><a class="nav-link" href="lorawan_conf"><?php echo _("LoRaWan"); ?></a></li>
              <?php endif; ?>
            </ul>
          </div>
        </li>
        <li class="nav-item" id="page_dct">
          <a class="nav-link navbar-toggle collapsed" id="dct" href="#" data-toggle="collapse" data-target="#navbar-collapse-dct">
              <i class="fas fa-exchange-alt fa-fw mr-2"></i>
              <span class="nav-label"><?php echo _("Data Collect"); ?></a>
          </a>
          <div class="collapse navbar-collapse" id="navbar-collapse-dct">
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item" name="dct_basic" id="dct_basic" ><a class="nav-link" href="basic_conf"><?php echo _("Basic"); ?></a></li>
              <li class="nav-item" name="interfaces" id="interfaces"><a class="nav-link" href="interfaces_conf"><?php echo _("Interfaces"); ?></a></li>
              <li class="nav-item" name="modbus" id="modbus"><a class="nav-link" href="modbus_conf"><?php echo _("Modbus Rules"); ?></a></li>
              <li class="nav-item" name="s7" id="s7"><a class="nav-link" href="s7_conf"><?php echo _("S7 Rules"); ?></a></li>
			        <li class="nav-item" name="fx" id="fx"><a class="nav-link" href="fx_conf"><?php echo _("FX Rules"); ?></a></li>
              <?php if ($model == "EG500" || $model == "EG410") : ?>
              <li class="nav-item" name="io" id="io"><a class="nav-link" href="io_conf"><?php echo _("IO"); ?></a></li>
              <?php endif; ?>
              <li class="nav-item" name="server" id="server"><a class="nav-link" href="server_conf"><?php echo _("Server"); ?></a></li>
              <li class="nav-item" name="opcua" id="opcua"><a class="nav-link" href="opcua"><?php echo _("OPC UA"); ?></a></li>
              <li class="nav-item" name="bacnet" id="bacnet"><a class="nav-link" href="bacnet"><?php echo _("BACnet Server"); ?></a></li>
              <li class="nav-item" name="datadisplay" id="datadisplay"><a class="nav-link" href="datadisplay"><?php echo _("Data Display"); ?></a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item" id="page_remote">
          <a class="nav-link navbar-toggle collapsed" id="remote" href="#" data-toggle="collapse" data-target="#navbar-collapse-remote">
              <i class="fas fa-server fa-fw mr-2"></i>
              <span class="nav-label"><?php echo _("Remote Manage"); ?></a>
          </a>
          <div class="collapse navbar-collapse" id="navbar-collapse-remote">
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item" name="ddns" id="ddns"> <a class="nav-link" href="ddns"><?php echo _("DDNS"); ?></a></li>
              <li class="nav-item" name="macchina" id="macchina"> <a class="nav-link" href="macchina"><?php echo _("Macchina"); ?></a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item" id="page_vpn">
          <a class="nav-link navbar-toggle collapsed" id="vpn" href="#" data-toggle="collapse" data-target="#navbar-collapse-vpn">
              <i class="fas fa-key fa-fw mr-2"></i>
              <span class="nav-label"><?php echo _("VPN"); ?></a>
          </a>
          <div class="collapse navbar-collapse" id="navbar-collapse-vpn">
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item" name="openvpn" id="openvpn"> <a class="nav-link" href="openvpn"><?php echo _("OpenVPN"); ?></a></li>
              <!-- <li class="nav-item" name="wireguard" id="wireguard"> <a class="nav-link" href="wireguard"><?php echo _("WireGuard"); ?></a></li> -->
            </ul>
          </div>
        </li>
          <?php if (RASPI_TORPROXY_ENABLED) : ?>
        <li class="nav-item">
           <a class="nav-link" href="torproxy_conf"><i class="fas fa-eye-slash fa-fw mr-2"></i><span class="nav-label"><?php echo _("TOR proxy"); ?></a>
        </li>
          <?php endif; ?>
          <?php if (RASPI_CONFAUTH_ENABLED) : ?>
        <li class="nav-item">
        <a class="nav-link" href="auth_conf"><i class="fas fa-user-lock fa-fw mr-2"></i><span class="nav-label"><?php echo _("Authentication"); ?></a>
        </li>
          <?php endif; ?>
          <?php if (RASPI_CHANGETHEME_ENABLED) : ?>
        <li class="nav-item">
          <a class="nav-link" href="theme_conf"><i class="fas fa-paint-brush fa-fw mr-2"></i><span class="nav-label"><?php echo _("Change Theme"); ?></a>
        </li>
          <?php endif; ?>
          <?php if (RASPI_VNSTAT_ENABLED) : ?>
        <li class="nav-item">
          <a class="nav-link" href="data_use"><i class="fas fa-chart-bar fa-fw mr-2"></i><span class="nav-label"><?php echo _("Data usage"); ?></a>
        </li>
          <?php endif; ?>
          <?php if (RASPI_SYSTEM_ENABLED) : ?>
        <li class="nav-item">
          <a class="nav-link" href="system_info"><i class="fas fa-cube fa-fw mr-2"></i><span class="nav-label"><?php echo _("System"); ?></a>
        </li>
          <?php endif; ?>
         <li class="nav-item">
          <a class="nav-link" href="about"><i class="fas fa-info-circle fa-fw mr-2"></i><span class="nav-label"><?php echo _("About Elastel"); ?></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <!-- <div class="text-center d-none d-md-block">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div> -->
      </ul>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
        <div class="load" id="loading" name="loading"></div>
        <?php
          if (RASPI_ENABLED) {
            $extraFooterScripts = array();
            // handle page actions
            switch ($page) {
            case "/wlan0_info":
                DisplayDashboard($extraFooterScripts);
                break;
            case "/dhcpd_conf":
                DisplayDHCPConfig();
                break;
            case "/wpa_conf":
                DisplayWPAConfig();
                break;
            case "/network_conf":
                DisplayNetworkingConfig();
                break;
            case "/hostapd_conf":
                DisplayHostAPDConfig();
                break;
            case "/detection_conf":
                DisplayDetectionConfig();
                break;
            case "/adblock_conf":
                DisplayAdBlockConfig();
                break;
            case "/openvpn":
                DisplayOpenVPNConfig();
                break;
            case "/wireguard":
                DisplayWireGuardConfig();
                break;
            case "/torproxy_conf":
                DisplayTorProxyConfig();
                break;
            case "/auth_conf":
                DisplayAuthConfig($config['admin_user'], $config['admin_pass']);
                break;
            case "/save_hostapd_conf":
                SaveTORAndVPNConfig();
                break;
            case "/theme_conf":
                DisplayThemeConfig($extraFooterScripts);
                break;
            case "/data_use":
                DisplayDataUsage($extraFooterScripts);
                break;
            case "/system_info":
                DisplaySystem();
                break;
            case "/about":
                DisplayAbout();
                break;
            case "/basic_conf":
                DisplayBasic();
                break;
            case "/interfaces_conf":
                DisplayInterfaces();
                break;
            case "/modbus_conf":
                DisplayModbus();
                break;
            case "/s7_conf":
                DisplayS7();
                break;
            case "/fx_conf":
                DisplayFx();
                break;
            case "/io_conf":
                DisplayIO();
                break;
            case "/server_conf":
                DisplayServer();
                break;
            case "/ddns":
                DisplayDDNS();
                break;
            case "/opcua":
                DisplayOpcua();
                break;
            case "/bacnet":
                DisplayBACnet();
                break;
            case "/datadisplay":
                dataDisplay();
                break;
            case "/macchina":
                DisplayMacchina();
                break;
            case "/lorawan_conf":
                DisplayLorawan();
                break;
            default:
                DisplayDashboard($extraFooterScripts);
            }            
          }
          ?>
        </div><!-- /.container-fluid -->
      </div><!-- End of Main Content -->
      <!-- Footer -->
      <footer class="sticky-footer bg-grey-100">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span></span>
          </div>
        </div>
      </footer>
      <!-- End Footer -->
    </div><!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top" style="display: inline;">
      <i class="fas fa-angle-up"></i>
    </a> 

    <!-- jQuery -->
    <script src="dist/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="dist/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="dist/jquery-easing/jquery.easing.min.js"></script>

    <!-- Chart.js JavaScript -->
    <script src="dist/chart.js/Chart.min.js"></script>

    <!-- SB-Admin-2 JavaScript -->
    <script src="dist/sb-admin-2/js/sb-admin-2.js"></script>

    <!-- Custom RaspAP JS -->
    <script src="app/js/custom.js"></script>

    <?php
    // Load non default JS/ECMAScript in footer.
    foreach ($extraFooterScripts as $script) {
        echo '<script type="text/javascript" src="' , $script['src'] , '"';
        if ($script['defer']) {
            echo ' defer="defer"';
        }
        echo '></script>' , PHP_EOL;
    }
    ?>
  </body>

<script type="text/javascript">
$(document).ready(function(){
  $('.sidebar li a').each(function(){
    if ($($(this))[0].href == String(window.location)) {
      $(this).parent().addClass('active');
    }
  });

  $('.nav-item').each(function() {
      if ($(this).hasClass('active')) {
        var id = $($(this))[0].id;
        if (id == "dct_basic" || id == "interfaces" || id == "modbus" || id == "s7" ||
            id == "server" || id == "io" || id == "bacnet" || id == "fx" || id == "datadisplay" ||
            id == "opcua") {
          $('#navbar-collapse-dct').addClass('show')
          $('#dct').removeClass('collapsed');
        } else if (id == "ddns" || id == "macchina") {
          $('#navbar-collapse-remote').addClass('show');
          $('#remote').removeClass('collapsed');
        } else if (id == "wan" || id == "lan" || id == "wifi" || id == "wifi_client" || 
          id == "online_detection" || id == "lorawan") {
          $('#navbar-collapse-network').addClass('show');
          $('#network').removeClass('collapsed');
        } else if (id == "openvpn" || id == "wireguard") {
          $('#navbar-collapse-vpn').addClass('show');
          $('#vpn').removeClass('collapsed');
        }
      }
  });

  $('.nav-item').click(function() {
    var id = $($(this))[0].id;
    if (id == "page_dct") {
      if ($('#navbar-collapse-remote').hasClass('show')) {
          $('#navbar-collapse-remote').removeClass('show');
          $('#remote').addClass('collapsed');
      }

      if ($('#navbar-collapse-network').hasClass('show')) {
          $('#navbar-collapse-network').removeClass('show');
          $('#network').addClass('collapsed');
      }

      if ($('#navbar-collapse-vpn').hasClass('show')) {
          $('#navbar-collapse-vpn').removeClass('show');
          $('#vpn').addClass('collapsed');
      }
    } else if (id == "page_remote") {
      if ($('#navbar-collapse-dct').hasClass('show')) {
          $('#navbar-collapse-dct').removeClass('show');
          $('#dct').addClass('collapsed');
      }

      if ($('#navbar-collapse-network').hasClass('show')) {
          $('#navbar-collapse-network').removeClass('show');
          $('#network').addClass('collapsed');
      }

      if ($('#navbar-collapse-vpn').hasClass('show')) {
          $('#navbar-collapse-vpn').removeClass('show');
          $('#vpn').addClass('collapsed');
      }
    } else if (id == "page_network") {
      if ($('#navbar-collapse-dct').hasClass('show')) {
          $('#navbar-collapse-dct').removeClass('show');
          $('#dct').addClass('collapsed');
      }

      if ($('#navbar-collapse-remote').hasClass('show')) {
          $('#navbar-collapse-remote').removeClass('show');
          $('#remote').addClass('collapsed');
      }

      if ($('#navbar-collapse-vpn').hasClass('show')) {
          $('#navbar-collapse-vpn').removeClass('show');
          $('#vpn').addClass('collapsed');
      }
    } else if (id == "page_vpn") {
      if ($('#navbar-collapse-dct').hasClass('show')) {
          $('#navbar-collapse-dct').removeClass('show');
          $('#dct').addClass('collapsed');
      }

      if ($('#navbar-collapse-remote').hasClass('show')) {
          $('#navbar-collapse-remote').removeClass('show');
          $('#remote').addClass('collapsed');
      }

      if ($('#navbar-collapse-network').hasClass('show')) {
          $('#navbar-collapse-network').removeClass('show');
          $('#network').addClass('collapsed');
      }
    }
  })
});
</script>
</html>
