# containers08

## Scopul lucrării

În cadrul acestei lucrări învățat să configurez integrarea continuă cu ajutorul Github Actions.

## Sarcina

Crearea unei aplicații Web, scrierea testelor pentru aceasta și configurarea integrării continue cu ajutorul Github Actions pe baza containerelor.

## Pregătire

Docker - instalat.

## Efectuarea lucrării

### Descrierea lucrului cu Git-ul

1. M-am conectat la contul meu de GitHub.
2. Am dat click pe "New repository".
3. Am denumit repository-ul, am bifat să fie inițializat cu un fișier nou `README.md` și l-am creat.
4. Am clonat repository-ul în Visual Studio (VS) Code:

   - `git clone https://github.com/anastasiaCazacu/containers08.git` - clonez repository-ul.
   - `cd containers08` - accesez folderul clonat.
   - `git checkout -B lab08` - creez branchiul si ma mut pe el si modific fisierul meu README.MD si ulterior doar adaug continutul.
   - `git add *` - adaug tot continutul.
   - `git status` - verific statutul si ma asigur ca am modificarile dorite.
   - `git commit -m "structure defined"` - creez commitul
   - `git push origin lab08` - push commit în depozitul de la distanță
   - `git checkout main`- Comut pe branch-ul principal
   - `git merge lab08`- Integrez (merge) branch-ul nou în main.
   - `git push origin main` - Împing schimbările pe GitHub.

   ### Descrierea efectuarii lucrarii

Creez structura propusa:

```text
site
├── modules/
│   ├── database.php
│   └── page.php
├── templates/
│   └── index.tpl
├── styles/
│   └── style.css
├── config.php
└── index.php
```

si fisierele propuse spre creare
`git add .`
`git commit -m "Initial commit "`
`git push`

![alt text](image-3.png)

- dupa mai multe incercari printre altele si calile gresite dar am obtinut in : Verificand în fila „Actions” pe GitHub dacă apare jobul și primesc ✅ Super tare! 🎉
  ![alt text](image-4.png)

## Răspunsul intrebarilor

1. Ce este integrarea continuă?
   Integrarea continuă (Continuous Integration - CI) este un proces prin care modificările aduse codului sunt testate și verificate automat, de fiecare dată când sunt adăugate în proiect (prin push sau pull request). Scopul acestui proces este de a detecta rapid eventualele erori și de a preveni integrarea codului defectuos.

2. Pentru ce sunt necesare testele unitare?
   Testele unitare verifică funcționarea corectă a părților mici (unități) ale aplicației — cum ar fi funcții, metode sau clase. Ele sunt necesare pentru:

```txt
a. Detectarea rapidă a bug-urilor;

b. Asigurarea că noile modificări nu strică funcționalitatea existentă;

c. Creșterea încrederii în cod (mai ales când sunt schimbări mari);

d. Automatizarea verificării codului în procesul de CI(Integrarea continuă).
```

3. Cât de des trebuie să fie executate testele unitare?

Ideal:

- La fiecare push (când cineva trimite cod nou);

- La fiecare pull request (înainte de a integra codul în ramura principală);

- Periodic, ex. noaptea (builduri programate).

4. Ce modificări trebuie făcute în `.github/workflows/main.yml` pentru a rula testele la fiecare Pull Request?

```yaml
on:
  push:
    branches: [main]
  pull_request:
    branches: [main]
```

Aceasta va rula acțiunile: când cineva dă `push` pe `main`, si când cineva face un `pull request` către `main`.

5. Ce trebuie adăugat în `.github/workflows/main.yml` pentru a șterge imaginile Docker după testare?

La sfârșitul `jobs` trebuie să adaugam un pas care șterge imaginile:

```yaml
- name: Cleanup Docker
  run: docker rmi $(docker images -q) || true
```

## Concluzie

In cadrul acest laborator am învățat să:

- Folosim GitHub Actions pentru integrarea continuă;

- Configurăm fișiere workflow pentru a testa automat codul;

- Verificăm funcționarea aplicației prin teste unitare;

- Întreținem un mediu curat prin ștergerea resurselor temporare (imagini Docker)

Integrarea continuă ajută la menținerea unui cod curat, sigur și funcțional, reducând riscul apariției de erori în versiunea finală a aplicației.
