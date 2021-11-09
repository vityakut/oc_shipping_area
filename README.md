[![ci](https://github.com/vityakut/oc_shipping_area/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/vityakut/oc_shipping_area/actions/workflows/ci.yml)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/vityakut/oc_shipping_area)
![latest-tag](https://img.shields.io/github/v/tag/vityakut/oc_shipping_area?colo)
![GitHub last commit](https://img.shields.io/github/last-commit/vityakut/oc_shipping_area)

![GitHub](https://img.shields.io/github/license/vityakut/oc_shipping_area)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/vityakut/oc_shipping_area?color)
![GitHub all releases](https://img.shields.io/github/downloads/vityakut/oc_shipping_area/total)
![PHP](https://img.shields.io/badge/php-%3E%3D7.0-blue)
![OpenCart](https://img.shields.io/badge/opencart-3-blue)




# Area Shipping
Модуль доставки с разделением по зонам и стоимостью ночной доставки.

Модуль позволяет установить разные цены для разных зон доставки. По каждой зоне можно настроить основную стоимость доставки, вторую стоимость доставки, зависимую от стоимости заказа, а так же отдельную стоимость ночной доставки. Ночная доставка может быть указана в форматах:
- +100 - прибавка к базовой/второй стоимости
- -100 - скидка от базовой/второй стоимости
- *1,5 - множитель к базовой/второй стоимости
- 100  - фиксированная стоимость. Не учитывает базовую стомость


Для корректной работы ночной доставки требуется модуль [quick-checkout](https://opencart3x.ru/module/order/quick-checkout)

## Requirements
PHP >=7.0

OpenCart 3

## Installation
[Скачать](https://github.com/vityakut/oc_shipping_area/releases) последний релиз

1. Загрузить модуль через админку сайта (в разделе Модули > Установка модулей)
2. Обновить кэш модификаторов (в разделе Модули > Модификаторы)
3. Перейти в Модули > Доставка, включить "Доставка по зонам"
4. Настроить модуль, там все понятно организовано, есть подсказки.

## Usage

Время заказа подтягивается из модуля quick-checkout