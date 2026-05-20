## Installation

Après avoir cloné le projet, corriger les permissions :
```bash
sudo chown -R $(id -u):$(id -g) ./app
```
Puis démarrer les conteneurs :
```bash
docker compose up -d
```

## 3. Rebuilder l'image

```bash
docker compose down
docker compose build php
docker compose up -d
```

---

Le `chown` dans le README est **indispensable** car le montage de volume Docker écrase toujours les permissions du dossier hôte.