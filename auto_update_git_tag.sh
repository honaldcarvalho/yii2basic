#!/bin/bash

# Configuração para que o script saia imediatamente se qualquer comando falhar
set -e

# Cores para mensagens no terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # Sem Cor

echo -e "${YELLOW}--- Automatizador de Tags Git (Incremento Automático) ---${NC}"

# Função para obter a última tag e calcular a próxima versão
get_and_increment_version() {
  local latest_tag_raw
  # Tenta pegar a última tag. Se não houver, retorna vazio.
  latest_tag_raw=$(git describe --tags --abbrev=0 2>/dev/null)

  local old_version
  local new_version

  if [ -z "$latest_tag_raw" ]; then
    echo -e "${YELLOW}Nenhuma tag encontrada no repositório. Iniciando com 'v0.0.0' como base e a nova tag será 'v0.0.1'.${NC}"
    old_version="v0.0.0" # Usado como "tag antiga" para o fluxo, embora não exista.
    new_version="v0.0.1" # A primeira tag a ser criada.
  else
    old_version="$latest_tag_raw"
    # Remove 'v' prefix if exists and split into parts (Major.Minor.Patch)
    local old_version_numeric="${old_version#v}"
    IFS='.' read -r major minor patch <<< "$old_version_numeric"

    # Incrementa a versão de patch
    local new_patch=$((patch + 1))

    # Constrói a nova versão numérica
    local new_version_numeric="$major.$minor.$new_patch"

    # Constrói a nova tag completa (com o 'v' novamente)
    new_version="v$new_version_numeric"
  fi
  echo "$old_version $new_version"
}

# Obter as versões automaticamente através da função
read OLD_VERSION NEW_VERSION <<< $(get_and_increment_version)

# Mensagem da tag: usa o primeiro argumento fornecido, senão usa um padrão
TAG_MESSAGE="${1:-Release $NEW_VERSION}"

echo -e "${YELLOW}Detectando versões:${NC}"
echo -e "${YELLOW}  Versão Antiga (base): ${OLD_VERSION}${NC}"
echo -e "${YELLOW}  Nova Versão (incrementada): ${NEW_VERSION}${NC}"
echo -e "${YELLOW}  Mensagem da Tag: '${TAG_MESSAGE}'${NC}"
echo -e "${YELLOW}Iniciando a atualização de tags Git de ${OLD_VERSION} para ${NEW_VERSION}...${NC}"

# 1. Deletar a tag antiga localmente (se existir e não for a tag base 'v0.0.0')
echo -e "${YELLOW}1. Verificando e deletando tag local: ${OLD_VERSION}...${NC}"
if [ "$OLD_VERSION" != "v0.0.0" ]; then # Só tenta deletar se não for a tag base
  if git rev-parse -q --verify "refs/tags/$OLD_VERSION" >/dev/null; then
    git tag -d "$OLD_VERSION"
    echo -e "${GREEN}Tag local ${OLD_VERSION} deletada com sucesso.${NC}"
  else
    echo -e "${YELLOW}Tag local ${OLD_VERSION} não encontrada. Pulando deleção local.${NC}"
  fi
else
  echo -e "${YELLOW}Tag base '${OLD_VERSION}'. Nenhuma tag local para deletar.${NC}"
fi

# 2. Deletar a tag antiga remotamente (se existir e não for a tag base 'v0.0.0')
echo -e "${YELLOW}2. Deletando tag remota: ${OLD_VERSION}...${NC}"
if [ "$OLD_VERSION" != "v0.0.0" ]; then # Só tenta deletar se não for a tag base
  if git ls-remote --tags origin | grep -q "refs/tags/$OLD_VERSION"; then
    git push origin ":refs/tags/$OLD_VERSION"
    echo -e "${GREEN}Tag remota ${OLD_VERSION} deletada com sucesso.${NC}"
  else
    echo -e "${YELLOW}Tag remota ${OLD_VERSION} não encontrada. Pulando deleção remota.${NC}"
  fi
else
  echo -e "${YELLOW}Tag base '${OLD_VERSION}'. Nenhuma tag remota para deletar.${NC}"
fi

# 3. Criar a nova tag localmente
echo -e "${YELLOW}3. Criando nova tag localmente: ${NEW_VERSION} com a mensagem '${TAG_MESSAGE}'...${NC}"
git tag -a "$NEW_VERSION" -m "$TAG_MESSAGE"
echo -e "${GREEN}Nova tag local ${NEW_VERSION} criada com sucesso.${NC}"

# 4. Enviar a nova tag remotamente
echo -e "${YELLOW}4. Enviando nova tag remotamente: ${NEW_VERSION}...${NC}"
git push origin "$NEW_VERSION"
echo -e "${GREEN}Nova tag remota ${NEW_VERSION} enviada com sucesso.${NC}"

echo -e "${GREEN}--- Processo de atualização de tags concluído com sucesso! ---${NC}"
echo -e "${GREEN}Nova tag '${NEW_VERSION}' criada e enviada com a mensagem: '${TAG_MESSAGE}'.${NC}"
echo -e "${YELLOW}Lembre-se de sempre verificar o status do seu repositório Git após operações como esta.${NC}"