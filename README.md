Vending Machine
=====================

This is a demo cli application written in PHP 7.4 and based on [Symfony 4](https://symfony.com/4).

Prerequisites
=============

- [Docker](https://docs.docker.com/)

Install
=======
```bash
git clone https://github.com/osorioramirez/vending_machine.git
cd vending_machine
docker-compose up -d
```

Usage
============================
## Available commands
```console
app:get            Get an item
app:insert         Insert a coin in the machine
app:reset          Reset vending machine (for debug)
app:return-coins   Return inserted coins
app:service:coins  Stock the machine with coins
app:service:items  Stock the machine with items
app:status         Show the machine status
```

### `app:get`
The `app:get` command allows to get an item:


```bash
docker exec -it vending_machine bin/console app:get WATER
```

Available items: `WATER, JUICE, SODA`


### `app:insert <coin>`
The `app:insert` command insert a coin in the machine:
  
```bash
docker exec -it vending_machine bin/console app:insert 0.25
```

Accepted coins: `0.05, 0.10, 0.25, 1.00`

### `app:return-coins`
The `app:return-coins` command return inserted coins:
  
```bash
docker exec -it vending_machine bin/console app:return-coins
```

### `app:service:items <name> <count>`
The `app:service:items` command stock the machine with items:

```bash
docker exec -it vending_machine bin/console app:service:items WATER 10
```

Available items: `WATER, JUICE, SODA`

### `app:service:coins <coin> <count>`
The `app:service:coins` command stock the machine with coins:

```bash
docker exec -it vending_machine bin/console app:service:coins 0.25 10
```

Accepted coins: `0.05, 0.10, 0.25, 1.00`

### `app:status`
The `app:status` command show the machine status:

```bash
docker exec -it vending_machine bin/console app:status
```

Run Tests
===============
```console
docker exec -it vending_machine composer test
```
