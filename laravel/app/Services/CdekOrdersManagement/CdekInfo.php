<?php
/**
 * Тарифы для ИМ:
 * 136 ИМ Посылка склад-склад (С-С)
 * 137 ИМ Посылка склад-дверь (С-Д)
 * 138 ИМ Посылка дверь-склад (Д-С)
 * 139 ИМ Посылка дверь-дверь (Д-Д)
 * 233 ИМ Экономичная посылка склад-дверь (С-Д)
 * 234 ИМ Экономичная посылка склад-склад (С-С)
 *
 * Тарифы для обычной доставки:
 * 1 Экспресс лайт дверь-дверь|дверь-дверь (Д-Д)|до 30 кг|Экспресс|Классическая экспресс-доставка по России документов и грузов до 30 кг.
 * 3 Супер-экспресс до 18|дверь-дверь (Д-Д)||Срочная доставка|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу.
 * 5 Экономичный экспресс склад-склад|склад-склад (С-С)||Экономичная доставка|Недорогая доставка грузов по России ЖД и автотранспортом (доставка грузов с увеличением сроков).
 * 10 Экспресс лайт склад-склад|склад-склад (С-С)|до 30 кг|Экспресс|Классическая экспресс-доставка по России документов и грузов.
 * 11 Экспресс лайт склад-дверь|склад-дверь (С-Д)|до 30 кг|Экспресс|Классическая экспресс-доставка по России документов и грузов.
 * 12 Экспресс лайт дверь-склад|дверь-склад (Д-С)|до 30 кг|Экспресс|Классическая экспресс-доставка по России документов и грузов.
 * 15 Экспресс тяжеловесы склад-склад|склад-склад (С-С)|от 30 кг|Экспресс|Классическая экспресс-доставка по России грузов.
 * 16 Экспресс тяжеловесы склад-дверь|склад-дверь (С-Д)|от 30 кг|Экспресс|Классическая экспресс-доставка по России грузов.
 * 17 Экспресс тяжеловесы дверь-склад|дверь-склад (Д-С)|от 30 кг|Экспресс|Классическая экспресс-доставка по России грузов.
 * 18 Экспресс тяжеловесы дверь-дверь|дверь-дверь (Д-Д)|от 30 кг|Экспресс|Классическая экспресс-доставка по России грузов.
 * 57 Супер-экспресс до 9|дверь-дверь (Д-Д)|до 5 кг|Срочная доставка|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу (доставка за 1-2 суток).
 * 58 Супер-экспресс до 10|дверь-дверь (Д-Д)|до 5 кг|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу (доставка за 1-2 суток).
 * 59 Супер-экспресс до 12|дверь-дверь (Д-Д)|до 5 кг|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу (доставка за 1-2 суток).
 * 60 Супер-экспресс до 14|дверь-дверь (Д-Д)|до 5 кг|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу (доставка за 1-2 суток).
 * 61 Супер-экспресс до 16|дверь-дверь (Д-Д)||Срочная доставка|Срочная доставка документов и грузов «из рук в руки» по России к определенному часу (доставка за 1-2 суток).
 * 62 Магистральный экспресс склад-склад|склад-склад (С-С)||Экономичнаядоставка|Быстрая экономичная доставка грузов по России
 * 63 Магистральный супер-экспресс склад-склад|склад-склад (С-С)||Экономичнаядоставка|Быстрая экономичная доставка грузов к определенному часу
 *
 *
 * Режимы доставки:
 * 1 | дверь-дверь | Д – Д | Курьер забирает груз у отправителя и доставляет получателю на указанный адрес.
 * 2 | дверь-склад | Д – С | Курьер забирает груз у отправителя и довозит до склада, получатель забирает груз самостоятельно в ПВЗ (самозабор).
 * 3 | склад-дверь | С – Д | Отправитель доставляет груз самостоятельно до склада, курьер доставляет получателю на указанный адрес.
 * 4 | склад-склад | С – С | Отправитель доставляет груз самостоятельно до склада, получатель забирает груз самостоятельно в ПВЗ (самозабор).
 *
 *
 *  Дополнительные услуги
 * 2    Нельзя добавлять в заказ, начисляется автоматически    СТРАХОВАНИЕ    "Обеспечение страховой защиты посылки. Размер дополнительного сбора страхования вычисляется от размера объявленной стоимости отправления. Услуга начисляется автоматически для всех заказов ИМ, не разрешена для самостоятельной передачи в тэге AddService."
 * 3    Можно добавить в заказ | ДОСТАВКА В ВЫХОДНОЙ ДЕНЬ    "Компания СДЭК осуществляет доставку и отправление документов и грузов в выходные и нерабочие дни. При доставке или отправлении документов или грузов в выходной день к базовому тарифу добавляется 300 руб."
 * 5    Нельзя добавлять в заказ, начисляется автоматически    ТЯЖЕЛЫЙ ГРУЗ    "При отправке тяжелых грузов,
 * 6    Нельзя добавлять в заказ, начисляется автоматически    НЕГАБАРИТНЫЙ ГРУЗ    "При доставке негабаритного Отправления, размер одной из сторон которого превышает 1,5 м тариф, увеличивается на 30 % (если отправление рассчитывается не по объемному весу). Стоимость и возможность доставки Отправления, размер одной из сторон которого превышает 2,2 м, согласовывается дополнительно при размещении заявки."
 * 7    Нельзя добавлять в заказ, начисляется сотрудником СДЭК    ОПАСНЫЙ ГРУЗ    "Кроме обычных документов и грузов, компания СДЭК готова доставить отправления, содержащие опасные грузы (кроме запрещенных к перевозке). В связи с определенным риском стоимость доставки грузов, относящихся к категории опасных, увеличивается в 1,5 раза."
 * 8    Нельзя добавлять в заказ, начисляется автоматически    ОЖИДАНИЕ БОЛЕЕ 15 МИН. У ОТПРАВИТЕЛЯ    "К приезду курьера Отправление должно быть подготовлено. По правилам компании СДЭК курьер может ожидать передачи или получения отправления не более 15 минут.В случаях, когда курьер дожидается приема или передачи Отправления более 15 минут, взимается дополнительный сбор в размере 170 рублей. Не допускается на тарифах Посылка."
 * 9    Нельзя добавлять в заказ, начисляется автоматически    ОЖИДАНИЕ БОЛЕЕ 15 МИН. У ПОЛУЧАТЕЛЯ
 * 10    Нельзя добавлять в заказ, начисляется автоматически    ХРАНЕНИЕ НА СКЛАДЕ    "При необходимости наша компания предоставляет возможность хранения груза на складе, первые 7 дней — БЕСПЛАТНО. Начиная с восьмых суток, плата за хранение осуществляется по следующим тарифам: стандартного отправления (1 место размером до 25*40*60см) - основной тариф 15 руб./место за 1 календарный день, включая выходные и праздничные дни;  не стандартного отправления (1 место размером более 25*40*60см) - основной тариф 30 руб./место за 1 календарный день, включая выходные и праздничные дни."
 * 13    Нельзя добавлять в заказ, начисляется сотрудником СДЭК    ПРОЧЕЕ    Дополнительный сбор от кредитного контроля СДЭК.
 * 14    Нельзя добавлять в заказ, начисляется автоматически    УДАЛЕННЫЙ РАЙОН    "В случаях, когда забор или доставка груза курьером осуществляется из удаленного района города, взимается дополнительная плата. Установленный перечень удаленных районов Вы можете узнать по телефону единой справочной службы 8-800-250-04-05 (звонок бесплатный) или у менеджеров компании в офисе Вашего города."
 * 15    Нельзя добавлять в заказ, начисляется автоматически    ПОВТОРНАЯ ПОЕЗДКА    "Когда требуется повторный вызов курьера по ранее аннулированному заказу либо доставка/забор не были осуществлены по вине клиента, начисляется дополнительный сбор. Размер сбора зависит от веса отправления и стоимости доставки по городу."
 * 16    Можно добавить в заказ | ЗАБОР В ГОРОДЕ ОТПРАВИТЕЛЕ    Дополнительная услуга забора груза в городе отправителя, при условии, что тариф доставки с режимом «от склада» (не доступна для тарифов Посылка)
 * 17    Можно добавить в заказ | ДОСТАВКА В ГОРОДЕ ПОЛУЧАТЕЛЕ    Дополнительная услуга доставки груза в городе получателя, при условии, что тариф доставки с режимом «до склада» (только для тарифов «Магистральный», «Магистральный супер-экспресс»)
 * 20    Нельзя добавлять в заказ, начисляется сотрудником СДЭК    ПЕНЯ    Дополнительный сбор от кредитного контроля СДЭК.
 * 23    Нельзя добавлять в заказ, начисляется сотрудником СДЭК    ОБРЕШЕТКА ГРУЗА    Для большей сохранности Вашего Отправления компания СДЭК предлагает услуги по обрешетке груза. Стоимость обрешетки для каждого Отправления рассчитывается индивидуально. Т.е. это индивидуальная упаковка груза.
 * 24    Доступно только в ЛК и при оформлении через Call-центр    УПАКОВКА 1    Стоимость коробки размером 310*215*280мм — 30 руб. (для грузов до 10 кг).
 * 25    Доступно только в ЛК и при оформлении через Call-центр    УПАКОВКА 2    Стоимость коробки размером 430*310*280мм — 45 руб. (для грузов до 15 кг).
 * 26    Только при оформлении через Call-центр    АРЕНДА КУРЬЕРА    "Когда необходимо доставить или принять документы в Федеральные службы, такие как: налоговые органы, министерства, посольства, суды, службы надзора и т.д., а также по заказам, требующим ожидания более часа, наша компания готова предоставить курьера. Стоимость услуги 170 руб./ час, минимальная оплата за 3 часа (510 руб)."
 * 27    Доступно только в ЛК и при оформлении через Call-центр    СМС УВЕДОМЛЕНИЕ    "Компания СДЭК предлагает каждому клиенту оформить услугу "смс-уведомление о доставке". Отправителю высылается сообщение с датой и временем доставки. Стоимость услуги 5 рублей."
 * 30    Можно добавить в заказ | ПРИМЕРКА НА ДОМУ    Курьер доставляет покупателю несколько единиц товара (одежда, обувь и пр.) для примерки. Время ожидания курьера в этом случае составляет 30 минут.
 * 32    Нельзя добавлять в заказ, начисляется автоматически в зависимости от учловий договора    СКАН ДОКУМЕНТОВ    Для подтверждения факта доставки мы можем предоставить Вам скан документов с подписью получателя. Стоимость услуги 50 руб.
 * 33    Доступно только в ЛК и при оформлении через Call-центр    ПОДЪЕМ НА ЭТАЖ РУЧНОЙ    "Услуга предоставляется при необходимости подъема на этаж крупногабаритных и тяжелых отправлений (весом от 10 кг).
 * 34    Доступно только в ЛК и при оформлении через Call-центр    ПОДЪЕМ НА ЭТАЖ ЛИФТОМ
 * 35    Нельзя добавлять в заказ, начисляется автоматически    ПРОЗВОН    Услуга для ИМ "Прозвон" включает в себя предварительный прозвон получателей перед доставкой операторами call-центра. Стоимость услуги 15 руб. 1 заказ.
 * 36    Можно добавить в заказ | ЧАСТИЧНАЯ ДОСТАВКА    "Во время доставки товара покупатель может отказаться от одной или нескольких позиций, и выкупить только часть заказа Если в заказе указано одно вложение, услуга не подключается."
 * 37    Можно добавить в заказ | ОСМОТР ВЛОЖЕНИЯ    Проверка покупателем содержимого заказа до его оплаты (вскрытие посылки).
 * 40    Нельзя добавлять в заказ, начисляется автоматически    ТЕПЛОВОЙ РЕЖИМ    Направления, по которым возможна доставка с тепловым режимом: Кемерово-Новокузнецк, Новосибирск-Красноярск, Новосибирск-Кемерово, Новосибирск-Томск, Новосибирск-Омск, Новосибирск-Барнаул, Барнаул-Горно-Алтайск И В ОБРАТНЫХ НАПРАВЛЕНИЯХ!
 * 41    Нельзя добавлять в заказ, начисляется автоматически    ВОЗВРАТ ДОКУМЕНТОВ    Служба доставки «СДЭК» предоставляет услугу обратной доставки сопроводительных документов с отметками получателя, а также возврат товаров для ИМ и других торговых предприятий.
 * 42    Нельзя добавлять в заказ, начисляется автоматически    АГЕНТСКОЕ ВОЗНАГРАЖДЕНИЕ    Наша компания оказывает услуги по приему  денежных средств от клиента за товар и РКО
 * 48    Можно добавить в заказ | Реверс    Обратный заказ на доставку от получателя до отправителя. Например, подписанные документы.
 *
 */