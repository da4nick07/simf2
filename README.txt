Отличия от "типовой"

1. Оно работает... И, надеюсь, прилично выглядит.
    Backgraund-color оставил для отладки, чтоб видеть / контролировать кто и от кого зависит.

2. Сделал локализацию (l10n), чтоб формы входа и регистрации ругались по-русски.

2. Оптимизировал запросы. Они возвращают не набор объектов, а массив данных для формирования соотв. страницы, блока (см. src/Reposytory/PostRepository).

3. Наследование шаблонов Twig (см. templates/base2.html.twig и templates/posts/index2.html.twig).

4. Базовый шаблон (templates/base2.html.twig) сделал на Bootstrap.

5. Подключил SCSS. Для главного меню задал стили (см. assets/styles/app.sccs).
    Конечно, в разметке надо делать мобильную версию (гл. меню сделать сверху, со сворачивание), но... Сейчас оно надо?

6. Добавил контроль доступа, через аннотации (см. src/Controller/PostController).
    Избиратели - наверное избыточно.
    Роли - иерархические.

7. Состав главного меню реагирует на права пользователя (см. templates/base2.html.twig блок #main-menu и src/Controller/PostController).

8. Шаблон для вывода статьи в списке /ленте (напр. на главной странице) задаём как параметр (см. src/Controller/PostController)

9. Весь блок со статьями на главной странице формируем в контроллере (см. src/Controller/PostController).

10. Добавил в статьи (Entity/Post) поле 'intro'.
    Можно и в запросе текст статьи обрезать... Но как-то...

11. Всем хочется сервисы... В смысле "расширить функциональность".
    Сделал (см. src/Service/TestSrv и src/Controller/PostController->post).

12. А если я не хочу своё куда-то выкладывать?.
    Или мне нужна просто маленькая, быстрая функция?
    Сделал (см. src/VLib/func и src/Controller/PostController->post).

13. Тестирование.
    Добавил тестовую БД, фикстуры и тесты (контроль доступа, авторизация, отправка формы, тестовые константы).

14. Добавил комментарии. Статусы - перечисление.

15. Добавил проверку на спам для комментариев. Асинхронно (Messenger), транспорт - Doctrine|Redis.
    + Тесты (моки, CompilerPass, теги сервисов)

16. Workflow
    Добавил StateMachine для обработки комментариев.
    Поскольку places дублируют статус комментария сделал их (places) хранение в поле статуса (поле 'state')

17. Админка, пользователи: форма фильтра (отбор по статусу), сортировка по колонкам (ajax, jQuery), подтверждение пользователя.

18. Админка, коментарии: форма фильтра (отбор по статусу, дате), сортировка по колонкам (ajax, jQuery), действия с коментариями.

19. Добил-таки отправку почты с хостинга... Плюс работа с секретами.
    Две недели (логи, сертификаты, ssh /консоль , секреты)... Но очень мозги прочищает. Очень полезно.

20. Больше недели бодания с техподдержкой хостинга...
    Результат: сайт работает с PHP v8.1.2, а вот ssh-консоль, по-умолчанию "... Работает с нативной версией PHP..". Т.е. v7.X.
    И это не изменить. Никак.
    Т.е. консоль Symfony толком не работает.

21. Комментарии. Уведомление админу о подозрительном коменте.
    Немножко полировки.

    Итого: для работы с очередями (асинхронности) нужен VDS или VPS.
    Жаль.


Исходники здесь: https://github.com/da4nick07/simf2

PS.
    Дамп БД (root / @root).
    Можно и миграцию сделать... Но всё время...
