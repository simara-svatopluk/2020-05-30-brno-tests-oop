---

# Svaťa Šimara

@snap[south span-100 text-gray text-08]
Pomáhám dostat logiku do entit
@snapend

---

# Testy ke kvalitnímu OOP

---

@snap[north span-100]
### Klasický nákupní košík
@snapend

@snap[midpoint span-55]
![IMAGE](assets/img/groceries-vector-full-shopping-bag-2.png)
@snapend

@snap[south span-100 text-gray text-08]
@[3](Co si představíte jako první?)
@snapend

---

@snap[north span-100]
### Klasický nákupní košík
@snapend

| product_id | amount |
|------------|--------|
| 44         | 3      |
| 55         | 1      |

@snap[south span-100 text-gray text-08]
@[1](Zapomeňme na uložení)
@[1-2](Soustřeďme se, že tato data potřebujeme vypsat)
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Triviální test
@snapend

```php
$cart = new Cart();
$cart->add('ab123');

$expected = [
    'ab123' => 1,
];

$actual = $cart->read();

Assert::assertSame($expected, $actual);
```

@snap[south span-100 text-gray text-08]
@[1]
@[1-2](Uživatelská akce)
@[4-7](Jak očekáváme, že bude vypadat výsledek)
@[8](Vnější pohled na košík)
@[4-8](Vnější pohled na košík)
@[1-10]
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Více stejných produktů
@snapend

```php
$cart = new Cart();
$cart->add('ab123');
$cart->add('ab123');

$expected = [
    'ab123' => 2,
];

Assert::assertSame($expected, $cart->read());
```

@snap[south span-100 text-gray text-08]
@[1]
@[1-3](Více akcí)
@[4-7](TA logika!)
@[4-7](Něco se děje, a nás zajímá výsledek)
@[1-9]
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Smazání produktu
@snapend

```php
$cart = new Cart();
$cart->add('ab123');
$cart->remove('ab123');

$expected = [];

Assert::assertSame($expected, $cart->read());
```

@snap[south span-100 text-gray text-08]
@[1]
@[1-2](Nejprve musíme košík naplnit)
@[1-3]
@[5](Očekáváme, že košík je opravdu prázdný)
@[1-7]
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Mazání neexistujícího
@snapend

```php
$cart = new Cart();
$cart->remove('ab123');

$expected = ???;
$expected = [];

Assert::assertSame($expected, $cart->read());

$this->expectException(ProductNotInCartException::class);
$cart->remove('ab123');
```

@snap[south span-100 text-gray text-08]
@[1-2]
@[1-4](Co se má vlastně stát?)
@[5](Možná budeme takovou situaci ignorovat)
@[1-2,5-7](Možná budeme takovou situaci ignorovat)
@[9](Anebo jde o situaci, která nemá nastat)
@[1-2,9-10](Anebo jde o situaci, která nemá nastat)
@[4](Tak či onak si musíme ujasnit očekávání)
@snapend

---

@snap[north-west span-50 text-center text-black]
### Při testování
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Vnější pohled na objekt
  - **`$cart->read()`**
- Ujasnit si, co má být výsledek
  - **`$expected = [...]`**
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Implementace
@snapend

```php
final class Cart
{
    public function add(string $id): void
    {
    }

    public function read(): array
    {
    }
    
    public function remove(string $id): void
    {
    }
}

```

@snap[south span-100 text-gray text-08]
Metody - neboli rozhraní - nemusíme vynalézat, je definováno testy
@snapend


---

@snap[north-west span-50 text-center text-black]
#### Implementace
@snapend

```php
final class Cart
{
    private array $products = [];
    public function add(string $id): void
    {
        if (!isset($this->products[$id])) {
            $this->products[$id] = 1;
        } else {
            $this->products[$id]++;
        }
    }
    public function read(): array
    {
        return $this->products;
    }
    public function remove(string $id): void
    {
        unset($this->products[$id]);
    }
}
```

@snap[south span-100 text-gray text-08]
@[3,12-15](Čtení může být triviální)
@[3,4-7,10,11](Pokud produkt v košíku není, tak ho přidáme jednou)
@[3,4-6,8-11](Pokud produkt v košíku je, počet inkrementujeme)
@[3,16-19](Odstaňování je triviální)
@[1-20]
@snapend

---

@snap[north-west span-75 text-center text-black]
### Složitější use-case
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- **Místnosti** v rámci košíku
- Pomáhají v orientaci ve velkých košících
- Místnosti obsahují produkty
- Hierarchicky neomezeně zanořené
@ulend
@snapend

---

@snap[north-west span-100 text-center text-black]
### Co Vás jako první napadne?
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Hierarchická data se blbě ukládají?
- Traverzování kolem stromu?
- Existuje plugin do Doctrine?
@ulend
@snapend


@snap[south span-100 text-gray text-08]
@[4](Na to všechno zapomeneme, a nejprve zjistíme, co se po nás chce.)
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Místnosti
@snapend

```php
$cart = new Cart();
$cart->addRoom('bathroom');

$expected = [
    'bathroom' => [
        'products' => [],
    ],
    'products' => [],
];
Assert::assertSame($expected, $cart->read());
```

@snap[south span-100 text-gray text-08]
@[1]
@[1,2](Přidání místnosti)
@[1-3, 10](Co vlastně očekáváme?)
@[4,8-9](Produkty mohou být v košíku bez místonosti)
@[5-7](Místnost ve očekávané struktuře, s produkty)
@[4-9](Ujasněné očekávání)
@[1-14]
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Místnosti
@snapend

```php
$cart = new Cart();
$cart->addRoom('hall-a');
$cart->addRoom('bathroom', 'hall-a');

$expected = [
    'hall-a' => [
        'bathroom' => [
            'products' => [],
        ],
        'products' => [],
    ],
    'products' => [],
];
Assert::assertSame($expected, $cart->read());
```

@snap[south span-100 text-gray text-08]
@[1]
@[1,2](Přidání místnosti)
@[1-3](Přidání zanořené místnosti)
@[1-3, 14](Co vlastně očekáváme?)
@[5,12-13](Produkty mohou být v košíku bez místonosti)
@[6,10,11](Místnost ve očekávané struktuře, s produkty)
@[7-9](Zanořená místnost)
@[4-13](Ujasněné očekávání)
@[1-14]
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Implementace
@snapend

```php
final class Cart
{
    private Room $room;

    public function __construct()
    {
        $this->room = new Room('');
    }

    public function addRoom(string $id, ?string $parentId = null): void
    {
        $this->room->add($id, $parentId);
    }

    public function read(): array
    {
        return $this->room->toArray();
    }
}
```

@snap[south span-100 text-gray text-08]
@[1,2,10,15](Rozhraní nám opět definují testy)
@[3,10-14](Místnosti reprezentují zanořenou strukturu, pojďme těžkou práci delegovat)
@[3,15-18](Delegováním dosáhneme krátkých objektů)
@[1-20]
@snapend

---

@snap[north-west span-50 text-center text-black]
### Delegování
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Není třeba dělat vše v košíku
- Definujeme, co bude dělat někdo jiný
- Nezajímá nás, jak to bude dělat
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Implementace Místnosti
@snapend

```php
final class Room
{
    private string $id;
    private array $roots = []; //self[]
    public function __construct(string $id)
    {
        $this->id = $id;
    }
    public function toArray(): array
    {
        $result = [];
        foreach ($this->roots as $room) {
            $result[$room->id] = $room->toArray();
        }
        $result['products'] = [];
        return $result;
    }
}
```

@[1,9]
@[3-8]
@[3,4,9-17]
@[1-20]

---

@snap[north-west span-50 text-center text-black]
#### Implementace Místnosti
@snapend

```php
final class Room
{
    private array $rooms = []; //self[]
    private array $roots = []; //self[]

    public function __construct(string $id){}

    public function add(string $id, ?string $parentId): void
    {
        $room = new self($id);

        $this->rooms[$id] = $room;

        if ($parentId === null) {
            $this->roots[] = $room;
        } else {
            $this->rooms[$parentId]->roots[] = $room;
        }
    }
}
```

@[1,8]
@[1,3,8-12]
@[1-4, 14-18]
@[1-22]

---

@snap[north-west span-50 text-center text-black]
#### Implementace Místnosti
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Jen jedna z možných implementací
- Zvenku netušíme, co je uvnitř
  - Že tam sou další objekty `Room`
  - Že je tam více plochých seznamů
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
### Zapouzdření
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Objekt zapouzdřuje data chováním
  - OOP!
- Nezajímá nás, co je uvnitř
- Zajímá nás, co objekt vrací a přijímá
@ulend
@snapend


@snap[south span-100 text-gray text-08]
@[1](A jak na to jste se dnes naučili)
@snapend

---

### Svaťa Šimara

http://svatasimara.cz/

---

@snap[north-west span-50 text-center text-black]
#### Testy ke kvalitnímu objektovému modelu
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Očekávání před prvním řádkem kódu
  - **`$expected = [...]`**
- Zapouzdření
  - **`$cart->read()`**
- Delegování
  - **`return $this->rooms->toArray()`**
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Pojmenování testů
@snapend

@snap[span-100]
@ul[list-spaced-bullets text-09]
- Podle názvu metody
  - **`testRead()`**
  - **`testRead2()`**
- Striktní pravidla
  - **`Given_EmptyCart_When_AddProduct_Then_ProductIsInCart`**
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Pojmenování testů
@snapend

@snap[span-100]
@ul[list-spaced-bullets text-09]
- Chci vyjádřit
  - Setup testu (precondition)
  - Akce
  - Očekávané chování
- Pomáhá mi `$expected`
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Pojmenování testů
@snapend

@snap[span-100]
@ul[list-spaced-bullets text-09]
- Přidáváme to prázdného košíku jeden produkt
  - očekávání?
  - Jeden produkt v košíku
  - `test AddingProduct To EmptyCart ResultsIn CartWithOneProduct`
  - `test` **$action** **$setup** `ResultsIn` **`$expected`**
@ulend
@snapend

---
@snap[north-west span-50 text-center text-black]
#### Pojmenování testů
@snapend

@snap[span-100]
@ul[list-spaced-bullets text-09]
- Mazání neexistující produktu
  - očekávání?
  - Vyhozená výjimka
  - `test` **$action** **setup** `ResultsIn` **`$expected`**
  - `test RemovingNotExistingProduct From NotEmptyCart ResultsIn ThrownException`
@ulend
@snapend

---

### Svaťa Šimara

http://svatasimara.cz/


