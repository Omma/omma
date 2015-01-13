require 'fileutils'
CONFIG = File.join(File.dirname(__FILE__), "config.rb")

$vb_gui = false
$vb_memory = 2048
$vb_cpus = 2
$shared_folder_type = "nfs"

if $shared_folder_type == "nfs"
  $mount_options = ['vers=3','udp','noatime','actimeo=2','fsc']
end

if File.exist?(CONFIG)
   require CONFIG
end



Vagrant.configure("2") do |config|
  ## Choose your base box
  #config.vm.box = "ubuntu/trusty64"
  config.vm.box = "box-cutter/ubuntu1404"
  config.vm.hostname = "omma"

  ## For masterless, mount your file roots file root
  config.vm.synced_folder "vm/salt/roots/", "/srv/"

  #config.vm.network "forwarded_port", guest: 80, host: 8080
  #config.vm.network "private_network", type: "dhcp"
  config.vm.synced_folder ".", "/vagrant", type: $shared_folder_type, mount_options: $mount_options
  if $shared_folder_type == "nfs"
    #config.bindfs.bind_folder "/vagrant", "/vagrant"
  end

  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = true
    config.hostmanager.aliases = %w(omma.dev prod.omma.dev)
  end

  config.vm.provider :virtualbox do |vb|
    vb.memory = $vb_memory
    vb.cpus = $vb_cpus
  end

  config.vm.provider "vmware_fusion" do |v|
    v.gui = $vb_gui
    v.vmx["memsize"] = $vb_memory
    v.vmx["numvcpus"] = $vb_cpus
  end

  # If true, then any SSH connections made will enable agent forwarding.
  # Default value: false
  config.ssh.forward_agent = true

  config.vm.provision "shell",
    inline: "apt-get install -y software-properties-common python-software-properties && add-apt-repository -y ppa:ondrej/php5-5.6 && apt-get -q update"

  ## Set your salt configs here
  config.vm.provision :salt do |salt|
    ## Minion config is set to ``file_client: local`` for masterless
    salt.minion_config = "vm/salt/minion"
    salt.colorize = true
    salt.log_level = "warning"

    salt.install_type = "git"
    salt.install_args = "v2014.1.12"

    ## Installs our example formula in "salt/roots/salt"
    salt.run_highstate = true

  end
end
