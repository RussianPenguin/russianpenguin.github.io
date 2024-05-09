---
layout: post
title: 'Linux: Система засыпает при заблокированном сеансе'
type: post
status: publish
categories:
- HowTo
tags:
- linux
permalink: "/2024/05/10/linux-sleep-on-login-screen"
---

Gnome и Kde страдают тем, что на экране логина за электропитание отвечает не утилита управления электропитанием, а logind из состава systemd и настроить поведение компьютера в этом режиме в панелях управления нельзя.

Проблема наблюдается как в gdm, так и sddm.

Проявляется в следующих случаях:
- Включили машину, но не вошли в систему **->** компьютер ушел в спячку
- Включили ноутбук с внешним монитором, поработали и заблокировали сеанс **->** система ушла в спячку если закрыть крышку 

Есть даже много отчетов об ошибках и информация от разработчиков о том, что это сделано "ради прохождения сертификации энергопотребления".

- [Баг в sddm на экране логина](https://github.com/sddm/sddm/issues/1148)
- [Прохождение сертификации Fedora](https://discussion.fedoraproject.org/t/gnome-suspends-after-15-minutes-of-user-inactivity-even-on-ac-power/79801)

Чтобы избежать засыпания системы после включения нужно сконфигурировать gdm отключив ему спячку (sddm, lxdm и т.п. работают нормально).

```shell
sudo -u gdm dbus-run-session gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-ac-timeout 0
```

Чтобы избежать проблемы засыпания ноутбука при закрытой крышке нужно дополнительно прописать настройки в `/etc/systemd/login.conf.d/00-lid.conf` или же в `/etc/systemd/login.conf`.

```
[Login]  
HandleLidSwitchExternalPower=ignore  
HandleLidSwitchDocked=ignore
```
