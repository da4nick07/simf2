Отличия от "типовой"

1. Оно работает... И, надеюсь, прилично выглядит.

2. Оптимизировал запросы. Они возвращают не набор объектов, а массив данных для формирования соотв. страницы (см. src/Reposytory/PostRepository).
    Ну и режимы гидрации.

3. Наследование шаблонов Twig (см. templates/base2.html.twig и templates/posts/index2.html.twig).

4. Базовый шаблон (templates/base2.html.twig) сделал на Bootstrap.

5. Подключил SCCS. Для главного меню задал стили (см. assets/styles/app.sccs).

6. Добавил контроль доступа, через аннотации (см. src/Controller/PostController).
    Понятно, что рефлексия - медленно, но пока оставил так.
    Роли - иерархические.

7. Состав главного меню реагирует на права пользователя (см. templates/base2.html.twig блок #main-menu и src/Controller/PostController).