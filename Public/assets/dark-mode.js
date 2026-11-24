(() => {
    const storageKey = 'escamboo-theme';
    const root = document.documentElement;
    let isDark = false;

    try {
        isDark = localStorage.getItem(storageKey) === 'dark';
    } catch (error) {
        console.warn('Não foi possível carregar a preferência de tema.', error);
    }

    const applyTheme = () => {
        root.dataset.theme = isDark ? 'dark' : 'light';
        toggleButton.setAttribute('aria-pressed', String(isDark));
        toggleButton.innerHTML = isDark
            ? '<i class="bi bi-sun-fill" aria-hidden="true"></i><span>Modo claro</span>'
            : '<i class="bi bi-moon-stars-fill" aria-hidden="true"></i><span>Modo escuro</span>';
    };

    const toggleButton = document.createElement('button');
    toggleButton.id = 'theme-toggle';
    toggleButton.type = 'button';
    toggleButton.setAttribute('aria-label', 'Alternar modo claro e escuro');
    toggleButton.addEventListener('click', () => {
        isDark = !isDark;
        applyTheme();

        try {
            localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
        } catch (error) {
            console.warn('Não foi possível salvar a preferência de tema.', error);
        }
    });

    const initializeHeaderMenus = () => {
        const headers = document.querySelectorAll('nav.escamboo-navbar, body > nav.navbar');

        headers.forEach((header, index) => {
            const container = header.querySelector('.escamboo-container, .container');
            const menu = container && container.lastElementChild;

            if (!container || !menu || !menu.querySelector('a')) {
                return;
            }

            const profileLink = menu.querySelector('a[href*="/trabalhador/perfil"]');
            const brandLink = container.querySelector('.escamboo-brand, .navbar-brand')
                || container.querySelector('a[href*="/trabalhador/servicos"]');
            const brandUrl = brandLink ? new URL(brandLink.href, window.location.href) : null;
            const appBasePath = brandUrl
                ? brandUrl.pathname.replace(/\/trabalhador\/servicos\/?$/, '').replace(/\/+$/, '')
                : '';
            const appBaseUrl = brandUrl ? `${brandUrl.origin}${appBasePath}` : '';
            const navigationOptions = [
                { path: '/trabalhador/servicos', label: 'Início', icon: 'bi-house-door-fill' },
                { path: '/matches', label: 'Matches', icon: 'bi-fire' },
                { path: '/acordos', label: 'Acordos', icon: 'bi-file-earmark-check-fill' },
                { path: '/denuncias/minhas', label: 'Minhas Denúncias', icon: 'bi-shield-exclamation' },
                { path: '/logout', label: 'Sair', icon: 'bi-box-arrow-right' }
            ];
            const existingLinks = Array.from(menu.querySelectorAll('a[href]'));
            const navigationLinks = navigationOptions.map((option) => {
                const existingLink = existingLinks.find((link) => {
                    const pathname = new URL(link.href, window.location.href).pathname.replace(/\/+$/, '');
                    return pathname.endsWith(option.path);
                });
                const link = existingLink || document.createElement('a');

                if (!existingLink) {
                    link.href = `${appBaseUrl}${option.path}`;
                    link.innerHTML = `<i class="bi ${option.icon}" aria-hidden="true"></i> ${option.label}`;
                }

                if (option.path === '/logout') {
                    link.classList.add('escamboo-header-menu-logout');
                }

                [link, ...link.querySelectorAll('*')].forEach((element) => {
                    element.classList.remove('d-none', 'd-sm-inline', 'd-lg-inline');
                });

                return link;
            });

            menu.replaceChildren(...navigationLinks);
            menu.classList.add('escamboo-header-menu');
            menu.hidden = true;
            menu.id = `escamboo-header-menu-${index + 1}`;

            const headerActions = document.createElement('div');
            headerActions.className = 'escamboo-header-actions';

            const menuButton = document.createElement('button');
            menuButton.type = 'button';
            menuButton.className = 'escamboo-header-menu-toggle';
            menuButton.setAttribute('aria-label', 'Abrir menu');
            menuButton.setAttribute('aria-controls', menu.id);
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';

            const closeMenu = () => {
                menu.hidden = true;
                menuButton.setAttribute('aria-expanded', 'false');
                menuButton.setAttribute('aria-label', 'Abrir menu');
                menuButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
            };

            menuButton.addEventListener('click', () => {
                const shouldOpen = menu.hidden;

                document.querySelectorAll('.escamboo-header-menu').forEach((openMenu) => {
                    openMenu.hidden = true;
                    const openButton = openMenu.parentElement.querySelector('.escamboo-header-menu-toggle');
                    if (openButton && openButton.classList.contains('escamboo-header-menu-toggle')) {
                        openButton.setAttribute('aria-expanded', 'false');
                        openButton.setAttribute('aria-label', 'Abrir menu');
                        openButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
                    }
                });

                if (shouldOpen) {
                    menu.hidden = false;
                    menuButton.setAttribute('aria-expanded', 'true');
                    menuButton.setAttribute('aria-label', 'Fechar menu');
                    menuButton.innerHTML = '<i class="bi bi-x-lg" aria-hidden="true"></i>';
                } else {
                    closeMenu();
                }
            });

            menu.addEventListener('click', (event) => {
                if (event.target.closest('a')) {
                    closeMenu();
                }
            });

            if (profileLink) {
                headerActions.appendChild(profileLink);
            }
            headerActions.appendChild(menuButton);
            container.insertBefore(headerActions, menu);
        });

        document.addEventListener('click', (event) => {
            document.querySelectorAll('.escamboo-header-menu:not([hidden])').forEach((menu) => {
                if (!menu.parentElement.parentElement.contains(event.target)) {
                    menu.hidden = true;
                    const menuButton = menu.parentElement.querySelector('.escamboo-header-menu-toggle');
                    if (menuButton && menuButton.classList.contains('escamboo-header-menu-toggle')) {
                        menuButton.setAttribute('aria-expanded', 'false');
                        menuButton.setAttribute('aria-label', 'Abrir menu');
                        menuButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
                    }
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                document.querySelectorAll('.escamboo-header-menu:not([hidden])').forEach((menu) => {
                    menu.hidden = true;
                    const menuButton = menu.parentElement.querySelector('.escamboo-header-menu-toggle');
                    if (menuButton && menuButton.classList.contains('escamboo-header-menu-toggle')) {
                        menuButton.setAttribute('aria-expanded', 'false');
                        menuButton.setAttribute('aria-label', 'Abrir menu');
                        menuButton.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
                        menuButton.focus();
                    }
                });
            }
        });
    };

    applyTheme();
    document.body.appendChild(toggleButton);
    initializeHeaderMenus();
})();
