# Task Creation Template

When creating new development tasks, follow this structured template:

## Template Structure
```markdown
## Task [Number] - [Brief Title]

### 1. Visão Geral
*   **Objetivo:** [1-2 sentences about main implementation goal]
*   **Contexto:** [Business justification, impact, and related functionalities]

### 2. Especificações Técnicas
*   **Endpoint:** `[METHOD]` `/api/v3/[path]`
*   **Regras de Negócio e Comportamento:**
    *   [Rule 1: Clear and concise description]
    *   [Rule 2: Include scenarios and relationships]
*   **Validações Chave:**
    *   `field_name`: [Rules: required, type, format, min/max, etc.]

### 4. Critérios de Aceitação e Entregáveis
*   **Testes Unitários:** [Essential test scenarios]
*   **Verificações Pós-Deploy:** [Manual/integration test scenarios]
*   **Documentação:** [ ] Update API documentation
```

## Key Principles
- Be specific about endpoints and HTTP methods
- Include business rules and validation details
- Specify implementation guidelines
- Define clear acceptance criteria
- Always include documentation requirements
```

## Naming Conventions
- **Entity**: Singular (Product, Customer)
- **Collection**: Plural (Products, Customers)  
- **Files**: Follow pattern `{Entity}Controller`, `{Entity}Data`, etc.
- **Routes**: Use kebab-case `/api/v3/price-lists`