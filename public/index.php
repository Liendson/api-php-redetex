<?php

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	
	require __DIR__ . '/../vendor/autoload.php';
	require 'connection.php';
	
	$app = AppFactory::create();

	$app->addBodyParsingMiddleware();
	$app->addRoutingMiddleware();
	$app->addErrorMiddleware(true, true, true);

	// MÉTODOS DE REQUISIÇÃO GET

	$app->get('/clientes', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();

		$query_sql = "SELECT * FROM tb_clientes";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado  = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});

	$app->get('/clientes/{id}', function (Request $request, Response $response, array $args) {

		$conexao   = getConnection();
		$numero_id = $args['id'];

		$query_sql = "SELECT * FROM tb_clientes WHERE id = $numero_id";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});

	$app->get('/orcamentos', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();

		$query_sql = "SELECT * FROM tb_orcamentos";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});

	$app->get('/orcamentos/{id}', function (Request $request, Response $response, array $args) {

		$conexao   = getConnection();
		$numero_id = $args['id'];

		$query_sql = "SELECT * FROM tb_orcamentos WHERE id = $numero_id";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});
	
	$app->get('/servicos', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();

		$query_sql = "SELECT * FROM tb_servicos";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado  = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});

	$app->get('/servicos/{id}', function (Request $request, Response $response, array $args) {

		$conexao   = getConnection();
		$numero_id = $args['id'];

		$query_sql = "SELECT * FROM tb_servicos WHERE id = $numero_id";
		$statement = $conexao->prepare($query_sql);
		$statement->execute();

		$resultado  = $statement->fetchAll();
		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		return $response->withHeader('Content-Type', 'application/json');

	});

	// MÉTODOS DE REQUISIÇÃO POST

	$app->post('/autenticar', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();

		$usuario = $dados["username"];
		$senha   = $dados["password"];

		$query_sql = "SELECT * FROM tb_usuarios WHERE usuario = ? AND senha = ?";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $usuario);
		$statement->bindValue(2, $senha);
		$statement->execute();

		$resultado = $statement->fetchAll();

		$encodeJSON = json_encode($resultado, JSON_PRETTY_PRINT);

		$response->getBody()->write($encodeJSON);
		$conexao = null;
		
		return $response;

	});

	$app->post('/clientes/inserir', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();
		
		$nome_cliente   = $dados["nomeCliente"];
		$tel_cliente    = $dados["telefoneCliente"];
		$nome_rua       = $dados["nomeRua"];
		$nome_edificio  = $dados["nomeEdificio"];
		$num_edificio   = $dados["numeroEdificio"];
		$apto_edificio  = $dados["numeroApartamento"];
		$pto_referencia = $dados["pontoReferencia"];

		$query_sql = "INSERT INTO tb_clientes(
					nome_cliente,
					telefone_cliente,
					nome_rua,
					nome_edificio,
					numero_edificio,
					apartamento_edificio,
					ponto_referencia) 
					VALUES (?, ?, ?, ?, ?, ?, ?)";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $nome_cliente);
		$statement->bindValue(2, $tel_cliente);
		$statement->bindValue(3, $nome_rua);
		$statement->bindValue(4, $nome_edificio);
		$statement->bindValue(5, $num_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $pto_referencia);
		$statement->execute();

		$conexao = null;
		
		$response->getBody()->write("{}");
		return $response;

	});

	$app->post('/orcamentos/inserir', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();

		$tpo_orcamento  = $dados["tipoOrcamento"];
		$nome_rua       = $dados["nomeRuaOrcamento"];
		$cep_rua        = $dados["cepRuaOrcamento"];
		$tel_cliente    = $dados["numeroTelefoneCliente"];
		$nome_edificio  = $dados["nomeEdificioOrcamento"];
		$apto_edificio  = $dados["numeroApartamentoOrcamento"];
		$num_edificio   = $dados["numeroEdificioOrcamento"];
		$pto_referencia = $dados["pontoReferenciaOrcamento"];
		$nome_cliente   = $dados["nomeClienteOrcamento"];
		$sit_orcamento  = 'Novo';
		
		$query_sql = "INSERT INTO tb_orcamentos(
					tp_orcamento,
					nome_rua,
					cep_rua,
					telefone_cliente,
					nome_edificio,
					apartamento_edificio,
					numero_edificio,
					ponto_referencia,
					nome_cliente,
					situacao_orcamento) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $tpo_orcamento);
		$statement->bindValue(2, $nome_rua);
		$statement->bindValue(3, $cep_rua);
		$statement->bindValue(4, $tel_cliente);
		$statement->bindValue(5, $nome_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $num_edificio);
		$statement->bindValue(8, $pto_referencia);
		$statement->bindValue(9, $nome_cliente);
		$statement->bindValue(10, $sit_orcamento);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;
		
	});

	$app->post('/servicos/inserir', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();

		$tp_servico     = $dados["tipoServico"];
		$cod_orcamento  = $dados["codOrcamento"];
		$nome_rua       = $dados["nomeRuaServico"];
		$cep_rua        = $dados["cepRuaServico"];
		$tel_cliente    = $dados["telefoneClienteServico"];
		$nome_edificio  = $dados["nomeEdificioServico"];
		$apto_edificio  = $dados["numeroApartamentoServico"];
		$num_edificio   = $dados["numeroEdificioServico"];
		$pto_referencia = $dados["pontoReferenciaServico"];
		$nome_cliente   = $dados["nomeClienteServico"];
		$sit_servico    = 'Novo';
		
		$query_sql = "INSERT INTO tb_servicos(
					tp_servico,
					nome_rua,
					cep_rua,
					telefone_cliente,
					nome_edificio,
					apartamento_edificio,
					numero_edificio,
					ponto_referencia,
					nome_cliente,
					situacao_servico) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $tp_servico);
		$statement->bindValue(2, $nome_rua);
		$statement->bindValue(3, $cep_rua);
		$statement->bindValue(4, $tel_cliente);
		$statement->bindValue(5, $nome_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $num_edificio);
		$statement->bindValue(8, $pto_referencia);
		$statement->bindValue(9, $nome_cliente);
		$statement->bindValue(10, $sit_servico);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;
	});

	// MÉTODOS DE REQUISIÇÃO PUT

	$app->put('/clientes/{id}/alterar', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();
		$num_id  = $args['id'];

		$nome_cliente   = $dados["nomeCliente"];
		$tel_cliente    = $dados["telefoneCliente"];
		$nome_rua       = $dados["nomeRua"];
		$nome_edificio  = $dados["nomeEdificio"];
		$apto_edificio  = $dados["numeroApartamento"];
		$num_edificio   = $dados["numeroEdificio"];
		$pto_referencia = $dados["obsAdicional"];
		
		$query_sql = "UPDATE tb_clientes SET 
					nome_cliente = ?,
					telefone_cliente = ?,
					nome_rua = ?,
					nome_edificio = ?,
					numero_edificio = ?,
					apartamento_edificio = ?,
					ponto_referencia = ? WHERE id = ?";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $nome_cliente);
		$statement->bindValue(2, $tel_cliente);
		$statement->bindValue(3, $nome_rua);
		$statement->bindValue(4, $nome_edificio);
		$statement->bindValue(5, $num_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $pto_referencia);
		$statement->bindValue(8, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	$app->put('/orcamentos/{id}/alterar', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();
		$num_id  = $args['id'];

		$tp_orcamento   = $dados["tipoOrcamento"];
		$nome_rua       = $dados["nomeRua"];
		$cep_rua        = $dados["cepRua"];
		$tel_cliente    = $dados["telefoneCliente"];
		$nome_edificio  = $dados["nomeEdificio"];
		$apto_edificio  = $dados["numeroApartamento"];
		$num_edificio   = $dados["numeroEdificio"];
		$pto_referencia = $dados["obsAdicional"];
		$nome_cliente   = $dados["nomeCliente"];
		$sit_orcamento  = $dados["situacaoOrcamento"];
		
		$query_sql = "UPDATE tb_orcamentos SET 
					tp_orcamento = ?,
					nome_rua = ?,
					cep_rua = ?,
					telefone_cliente = ?,
					nome_edificio = ?,
					apartamento_edificio = ?,
					numero_edificio = ?,
					ponto_referencia = ?,
					nome_cliente = ?,
					situacao_orcamento = ? WHERE id = ?";


		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $tp_orcamento);
		$statement->bindValue(2, $nome_rua);
		$statement->bindValue(3, $cep_rua);
		$statement->bindValue(4, $tel_cliente);
		$statement->bindValue(5, $nome_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $num_edificio);
		$statement->bindValue(8, $pto_referencia);
		$statement->bindValue(9, $nome_cliente);
		$statement->bindValue(10, $sit_orcamento);
		$statement->bindValue(11, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	$app->put('/servicos/{id}/alterar', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$dados   = $request->getParsedBody();
		$num_id  = $args['id'];

		$tp_servico     = $dados["tipoServico"];
		$nome_rua       = $dados["nomeRua"];
		$cep_rua        = $dados["cepRua"];
		$tel_cliente    = $dados["telefoneCliente"];
		$nome_edificio  = $dados["nomeEdificio"];
		$apto_edificio  = $dados["numeroApartamento"];
		$num_edificio   = $dados["numeroEdificio"];
		$pto_referencia = $dados["obsAdicional"];
		$nome_cliente   = $dados["nomeCliente"];
		$sit_servico    = $dados["situacaoServico"];

		$query_sql = "UPDATE tb_servicos SET 
					tp_servico = ?,
					nome_rua = ?,
					cep_rua = ?,
					telefone_cliente = ?,
					nome_edificio = ?,
					apartamento_edificio = ?,
					numero_edificio = ?,
					ponto_referencia = ?,
					nome_cliente = ?,
					situacao_servico = ? WHERE id = ?";


		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $tp_servico);
		$statement->bindValue(2, $nome_rua);
		$statement->bindValue(3, $cep_rua);
		$statement->bindValue(4, $tel_cliente);
		$statement->bindValue(5, $nome_edificio);
		$statement->bindValue(6, $apto_edificio);
		$statement->bindValue(7, $num_edificio);
		$statement->bindValue(8, $pto_referencia);
		$statement->bindValue(9, $nome_cliente);
		$statement->bindValue(10, $sit_servico);
		$statement->bindValue(11, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	// MÉTODOS DE REQUISIÇÃO DELETE

	$app->delete('/clientes/{id}/remover', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$num_id  = $args['id'];

		$query_sql = "DELETE FROM tb_clientes WHERE id = ?";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	$app->delete('/orcamentos/{id}/remover', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$num_id  = $args['id'];

		$query_sql = "DELETE FROM tb_orcamentos WHERE id = ?";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	$app->delete('/servicos/{id}/remover', function (Request $request, Response $response, array $args) {

		$conexao = getConnection();
		$num_id  = $args['id'];

		$query_sql = "DELETE FROM tb_servicos WHERE id = ?";

		$statement = $conexao->prepare($query_sql);
		$statement->bindValue(1, $num_id);
		$statement->execute();
		
		$conexao = null;

		$response->getBody()->write("{}");
		return $response;

	});

	$app->run();