<?php

namespace App\Core;

class Controller
{
    public function view(string $view, ?array $data = null) //carrega as views do projeto, esse é o controller "PAI", tendo todas as funções que os outros controllers vão herdar, como a função view, que carrega as views do projeto, e a função redirect, que redireciona para outra página.
    {
        if ($data) {
            extract($data);
        }

        $path = __DIR__ . "/../Views/$view.php";

        if (file_exists($path)) {
            ob_start();
            require_once $path;
            $html = ob_get_clean();

            $assetBase = htmlspecialchars(URL_BASE, ENT_QUOTES, 'UTF-8') . '/assets';
            $themeCssPath = __DIR__ . '/../../Public/assets/dark-mode.css';
            $themeCssHash = is_file($themeCssPath) ? hash_file('sha256', $themeCssPath) : false;
            $themeCssVersion = $themeCssHash !== false ? substr($themeCssHash, 0, 12) : '1';
            $themeAssets = '<link rel="stylesheet" href="' . $assetBase . '/dark-mode.css?v=' . $themeCssVersion . '">'
                . '<script defer src="' . $assetBase . '/dark-mode.js"></script>';

            if (! preg_match('/<link\b(?=[^>]*\brel\s*=\s*["\'](?:shortcut\s+)?icon(?:\s+[^"\']*)?["\'])[^>]*>/i', $html)) {
                $faviconUrl = htmlspecialchars(URL_BASE, ENT_QUOTES, 'UTF-8') . '/assets/logoEscamboo.png';
                $themeAssets = '<link rel="icon" type="image/png" href="' . $faviconUrl . '">' . $themeAssets;
            }

            $html = preg_replace('/<\/head>/i', $themeAssets . '</head>', $html, 1);

            $usuario = $_SESSION['usuario_logado'] ?? [];
            if (
                ($usuario['Tipo'] ?? '') === 'trabalhador' &&
                ! in_array($view, ['Trabalhador/preferencias', 'Trabalhador/editarPerfil'], true) &&
                empty($_SESSION['aviso_perfil_dispensado']) &&
                trim((string) ($usuario['Biografia'] ?? '')) === ''
            ) {
                $editarPerfilUrl = htmlspecialchars(URL_BASE . '/trabalhador/editar', ENT_QUOTES, 'UTF-8');
                $dispensarUrl = htmlspecialchars(URL_BASE . '/trabalhador/dispensar-aviso-perfil', ENT_QUOTES, 'UTF-8');
                $avisoPerfil = '<div id="aviso-perfil-modal" role="dialog" aria-modal="true" aria-labelledby="aviso-perfil-titulo" style="position: fixed; inset: 0; z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(25, 15, 15, .65);">'
                    . '<section style="width: 100%; max-width: 460px; padding: 2rem; border-radius: 20px; background: #fcf9f2; color: #2b1f1d; text-align: center; box-shadow: 0 1rem 3rem rgba(0, 0, 0, .25);">'
                    . '<div aria-hidden="true" style="font-size: 2.5rem; color: #ba8238; margin-bottom: .5rem;">&#9734;</div>'
                    . '<h2 id="aviso-perfil-titulo" style="margin: 0 0 .75rem; color: #590b1c; font-size: 1.5rem; font-weight: 700;">Ei, termine de configurar seu perfil!</h2>'
                    . '<p style="margin: 0 0 1.5rem;">As pessoas querem te conhecer. Conte um pouco sobre você e seu trabalho.</p>'
                    . '<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: .75rem;">'
                    . '<a href="' . $editarPerfilUrl . '" style="display: inline-block; padding: .75rem 1.25rem; border-radius: 999px; background: #590b1c; color: #fff; font-weight: 700; text-decoration: none;">Editar perfil</a>'
                    . '<button id="aviso-perfil-mais-tarde" type="button" style="padding: .75rem 1.25rem; border: 1px solid #73675a; border-radius: 999px; background: transparent; color: #2b1f1d; font: inherit; cursor: pointer;">Mais tarde</button>'
                    . '</div></section></div>'
                    . '<script>(function(){var modal=document.getElementById("aviso-perfil-modal");var botao=document.getElementById("aviso-perfil-mais-tarde");if(!modal||!botao)return;botao.addEventListener("click",function(){modal.remove();fetch("' . $dispensarUrl . '",{method:"POST",credentials:"same-origin",headers:{"X-Requested-With":"XMLHttpRequest"}}).then(function(resposta){if(!resposta.ok){throw new Error("Não foi possível dispensar o aviso do perfil.");}}).catch(function(erro){console.error(erro);});});})();</script>';
                $html = preg_replace('/<\/body>/i', $avisoPerfil . '</body>', $html, 1);
            }

            echo $html;
        } else {
            print 'A view solicitada não foi encontrada: ' . $view;
        }
    }

    public function redirect(string $url)
    {
        header('Location: ' . $url);
        exit();
    }

    public function autenticacaoRequired() //loginRequired, verifica se o usuário está logado, caso não esteja, redireciona para a página de login.
    {
        if (!isset($_SESSION['usuario_logado'])) {
            $_SESSION['erro'] = 'Você precisa estar logado para acessar esta página.';
            $this->redirect(URL_BASE . '/login');
        }

        return true;
    }

    public function adminRequired() //verifica o tipo do usuario, caso não seja admin, redireciona para a página de login, é pra evitar que os trabahadores tenham acesso a dados sensiveis
    {
        if (
            !isset($_SESSION['usuario_logado']) ||
            $_SESSION['usuario_logado']['Tipo'] !== 'adm'
        ) {
            $this->redirect(URL_BASE . '/login');
        }

        return true;
    }
}