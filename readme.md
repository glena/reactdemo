## Demoreact

Playing with Laravel, ReactPHP & Ratchet. Demo para LaravelBA, meetup group de Laravel en Buenos Aires: http://laravelba.com.ar

### Presentación

http://germanlena.com.ar/reactdemo

### Video

https://www.youtube.com/watch?v=_avhGaq9-Bg

### Links:
* State pattern: http://es.wikipedia.org/wiki/State_(patr%C3%B3n_de_dise%C3%B1o)
* Ratchet & Laravel: https://medium.com/laravel-4/laravel-4-real-time-chat-eaa550829538
* ReactPHP: http://reactphp.org/
* Ratchet: http://socketo.me/docs/install

### Config:

* app.php: streams_port 7778
* Add in your hosts file the following line

```
127.0.0.1 demoreact.app
```

### To run:

This vagrant config is a standard Homestead configuration. It may produce conflicts with other running Homestead VMs.

First, update your home path to point to your key files on the Homestead.yaml file (attributes: keys & authorize).

Run:

```
vagrant up
vagrant ssh

cd ./Code/demoreact

#probably will need to update composer with
#sudo /usr/local/bin/composer self-update

composer install
php artisan rt:listen
```

### Homestead changes

To run it on a standard Homestead (you may have one running for other projects) you need to add a rule to forward the port used by ReactPHP (port 7778).

Add this line on homestead.rb under "Configure Port Forwarding To The Box" section

```
config.vm.network "forwarded_port", guest: 7778, host: 7778
```

[![Analytics](https://ga-beacon.appspot.com/UA-51467836-1/glena/demoreact)](http://germanlena.com.ar)
