Módulo de integração AnyMarket e Magento 1.x.x
===========================================
---
Versão atual:
---------
**2.0.0**
-----

**(IMPORTANTE) ATUALIZAÇÃO**
========================

> Se há instalado no Magento uma versão anterior a 2.x.x é necessário a
> exclusão manual do arquivo localizado em: 
> **app/etc/modules/Anymarket_Catalog.xml**
>
> E de toda a pasta localizada em:
> **app/code/community/Anymarket/Catalog**
>
> Sem seguir esses passos sua **integração não funcionara corretamente**.
> **Nenhum dado será perdido da versão anterior.**

Descrição
---------
Olá! Com o módulo de integração [AnyMarket] instalado e configurado será possível a integração automática de:

 - Produtos
 - Pedidos
 - Estoque

Instalação
----------
**Fique ligado nas dicas que vão ajudar ter sucesso na sua instalação**

 - Realize um Backup do Magento completo.
 - Certifique-se que não há outros módulos [AnyMarket] instalados em seu sistema.
 - Baixe o repositório como arquivo zip ou faça um fork do projeto.
 - Copie o diretório **app** para dentro do diretório do magento.
 - Force a limpeza do cache **Sistema > Gerenciamento de cache** (System > Cache management)
 - Faça o logof e logue novamente
 - Estará disponível a opção **Sistema > AnyMarket** (System > AnyMarket)

Requisitos mínimos
------------------
 - [PHP] 5.4+
 - [Magento] 1.7.x 
  
 
Mais informações ou parcerias
--------
Caso tenha dúvidas, estamos à disposição para atendê-lo no que for preciso: http://www.anymarket.com.br/ ou em nosso [blog].

Desenvolvedores
----
Caso precise de informações sobre a API [AnyMarket] você encontra clicando em: http://developers.anymarket.com.br/
 
Licença
-------
Este código fonte está licenciado sob os termos da **Mozilla Public License, versão 2.0**. Caso não encontre uma cópia distribuida com os arquivos, você pode obter uma em: https://mozilla.org/MPL/2.0/. 

This Source Code Form is subject to the terms of the **Mozilla Public License, v. 2.0**. If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.

Contribuições
-------------
Caso tenha encontrado ou corrigido um bug ou tem alguma fature em mente e queira dividir com a equipe [AnyMarket] ficamos muito gratos e sugerimos os passos a seguir:

 * Faça um fork.
 * Adicione sua feature ou correção de bug.
 * Envie um pull request no [GitHub].
 
 Magento 2
 -------------
 Caso seu Magento esteja na versão 2.x.x realizar a instalação do módulo disponivel em [Anymarket Magento2].
 

 [Magento]: https://www.magentocommerce.com/
 [PHP]: http://www.php.net/
 [AnyMarket]: http://www.anymarket.com.br
 [GitHub]: https://github.com/AnyMarket/magentoService
 [blog]: http://marketplace.anymarket.com.br/
 [Anymarket Magento2]: https://github.com/AnyMarket/magento2
