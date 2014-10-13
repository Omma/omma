require 'fileutils'
CONFIG = File.join(File.dirname(__FILE__), "config.rb")

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
  config.vm.box = "ubuntu/trusty64"

  ## For masterless, mount your file roots file root
  config.vm.synced_folder "vm/salt/roots/", "/srv/"

  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "192.168.50.4"
  config.vm.synced_folder ".", "/vagrant", type: $shared_folder_type, mount_options: $mount_options

  config.vm.provider :virtualbox do |vb|
    vb.memory = $vb_memory
    vb.cpus = $vb_cpus
  end

  # If true, then any SSH connections made will enable agent forwarding.
  # Default value: false
  config.ssh.forward_agent = true

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
