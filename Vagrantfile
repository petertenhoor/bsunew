# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"

  config.vm.provider :virtualbox do |virtualbox|
    virtualbox.customize ["modifyvm", :id, "--memory", "2048", "--cpus", "2", "--ioapic", "on"]
  end
  
  config.vm.synced_folder ".", "/vagrant", :nfs => true
  config.vm.network :forwarded_port, host:8080, guest:80
  config.vm.network :forwarded_port, host:8443, guest:443
  config.vm.network :private_network, ip: "192.168.50.3" #random value

  config.vm.provision "shell" do |s|
    s.path = "vagrant.sh"
    s.args   = "'/vagrant'"
  end
end