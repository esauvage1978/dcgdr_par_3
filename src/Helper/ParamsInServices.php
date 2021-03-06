<?php

declare(strict_types=1);

namespace App\Helper;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function in_array;

/**
 * Récupération des paramètres présents dans le fichier config/service.yaml
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class ParamsInServices
{
    public const ES_DIRECTORY_FIXTURES_DOC = 'es.directory.fixtures.doc';
    public const ES_DIRECTORY_FIXTURES_JSON = 'es.directory.fixtures.json';
    public const ES_APP_NAME = 'es.app.name';
    public const ES_DIRECTORY_AVATAR = 'es.directory.avatar';
    public const ES_DIRECTORY_CSS = 'es.directory.css';
    public const ES_DIRECTORY_UPLOAD_ACTION = 'es.directory.upload.action';
    public const ES_MAILER_OBJECT_PREFIXE = 'es.mailer.object.prefixe';
    public const ES_MAILER_USER_NAME = 'es.mailer.user.name';
    public const ES_MAILER_USER_MAIL = 'es.mailer.user.mail';
    public const ES_MAILER_USER_PASSWORD = 'es.mailer.user.password';
    public const ES_MAILER_SMTP_HOST = 'es.mailer.smtp.host';
    public const ES_MAILER_SMTP_PORT = 'es.mailer.smtp.port';
    public const ES_NEWS_TIME = 'es.news.time';
    public const ES_JALON_TO_NEAR = 'es.jalon.to.near';
    public const ES_TREE_UNDEVELOPPED_NBR = 'es.tree.undevelopped.nbr';
    public const ES_MAILER_WORKFLOW_COTECH='es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_CODIR = 'es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_REJECTED='es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_FINALISED='es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_DEPLOYED = 'es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_MEASURED = 'es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_CLOTURED='es.mailer.workflow.cotech';
    public const ES_MAILER_WORKFLOW_ABANDONNED='es.mailer.workflow.abandonned';

    /** @var ParameterBagInterface */
    private $params;

    /** @var array $datas */
    private $datas = [];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->datas = [
            self::ES_APP_NAME,
            self::ES_DIRECTORY_AVATAR,
            self::ES_DIRECTORY_CSS,
            self::ES_DIRECTORY_UPLOAD_ACTION,
            self::ES_MAILER_OBJECT_PREFIXE,
            self::ES_MAILER_USER_NAME,
            self::ES_MAILER_USER_MAIL,
            self::ES_MAILER_USER_PASSWORD,
            self::ES_MAILER_SMTP_HOST,
            self::ES_MAILER_SMTP_PORT,
            self::ES_NEWS_TIME,
            self::ES_JALON_TO_NEAR,
            self::ES_TREE_UNDEVELOPPED_NBR,
            self::ES_DIRECTORY_FIXTURES_DOC,
            self::ES_DIRECTORY_FIXTURES_JSON,
            self::ES_MAILER_WORKFLOW_COTECH,
            self::ES_MAILER_WORKFLOW_CODIR,
            self::ES_MAILER_WORKFLOW_REJECTED,
            self::ES_MAILER_WORKFLOW_FINALISED,
            self::ES_MAILER_WORKFLOW_DEPLOYED,
            self::ES_MAILER_WORKFLOW_MEASURED,
            self::ES_MAILER_WORKFLOW_CLOTURED,
            self::ES_MAILER_WORKFLOW_ABANDONNED,

        ];
    }

    /**
     * Récupère la valeur paramètre présente dans le fichiers config/services.yaml.
     * Utiliser les constantes présentes dans cette classe
     *
     * @param string $param_name
     * @return string
     */
    public function get(string $param_name): string
    {
        if (!in_array($param_name, $this->datas)) {
            throw new InvalidArgumentException('Ce paramètre est inconnu : ' . $param_name);
        }

        return $this->params->get($param_name);
    }
}
