---

# Svaťa Šimara

@snap[south span-100 text-gray text-08]
Pomáhám dostat logiku do entit
@snapend

---

# Testy ke kvalitnímu OOP

---

@snap[north-west span-50 text-center text-black]
#### Nákupní košík
@snapend

@snap[east span-45]
![IMAGE](assets/img/groceries-vector-full-shopping-bag-2.png)
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Nákupní košík na e-shopu
- Přidání do košíku, odebrání
- Produkt může být v košíku vícekrát (množství)
@ulend
@snapend

---

@snap[north-west span-50 text-center text-black]
#### Datová struktura
@snapend

| item_id | cart_id | product_id | amount |
|---------|---------|------------|--------|
| 1       | 1       | 44         | 1      |
| 2       | 1       | 55         | 1      |
| 3       | 2       | 15         | 2      |
| 4       | 2       | 87         | 1      |

@[1,2]

---

@snap[north-west span-50 text-center text-black]
#### Datová struktura
@snapend

```sql zoom-18
CREATE TABLE "cart_item" (
    "id" serial NOT NULL PRIMARY KEY,
    "cart_id" integer NOT NULL,
    "product_id" integer NOT NULL,
    "amount" integer NOT NULL
);
```

---

@snap[north-west span-50 text-center text-black]
#### Triviální začátek
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
- Opět - TA logika
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
### Zapouzdření
@snapend

@snap[span-75]
@ul[list-spaced-bullets text-09]
- Zajímá nás jen, co košík umí a co vrací
- V testech nás nezajímá, co je uvnitř
- Prostor pro refaktoring
- Uvnitř objektů můžeme použít cokoliv (knihovnu, struktury, ...)
@ulend
@snapend

---




### Add Some Slide Candy

![IMAGE](assets/img/presentation.png)

---?color=linear-gradient(180deg, white 75%, black 25%)
@title[Customize Slide Layout]

@snap[west span-55]
## Customize the Layout
@snapend

@snap[north-east span-45]
![IMAGE](assets/img/presentation.png)
@snapend

@snap[south span-100]
Snap Layouts let you create custom slide designs directly within your markdown.
@snapend

---
@title[Add A Little Imagination]

@snap[north-west span-50 text-center]
#### Engage your Audience
@snapend

@snap[west span-55]
@ul[list-spaced-bullets text-09]
- You will be amazed
- What you can achieve
- With a **little imagination**
- And GitPitch Markdown
@ulend
@snapend

@snap[east span-45]
![IMAGE](assets/img/conference.png)
@snapend

@snap[south span-100 bg-black fragment]
@img[shadow](assets/img/conference.png)
@snapend

---

@snap[north-east span-100 text-pink text-06]
Let your code do the talking!
@snapend

```sql zoom-18
CREATE TABLE "topic" (
    "id" serial NOT NULL PRIMARY KEY,
    "forum_id" integer NOT NULL,
    "subject" varchar(255) NOT NULL
);
ALTER TABLE "topic"
ADD CONSTRAINT forum_id
FOREIGN KEY ("forum_id")
REFERENCES "forum" ("id");
```

@snap[south span-100 text-gray text-08]
@[1-5](You can step-and-ZOOM into fenced-code blocks, source files, and Github GIST.)
@[6,7, zoom-13](Using GitPitch live code presenting with optional annotations.)
@[8-9, zoom-12](This means no more switching between your slide deck and IDE on stage.)
@snapend


---?image=assets/img/code.jpg&opacity=60&position=left&size=45% 100%

@snap[east span-50 text-center]
## Now It's **Your** Turn
@snapend

@snap[south-east span-50 text-center text-06]
[Download GitPitch Desktop @fa[external-link]](https://gitpitch.com/docs/getting-started/tutorial/)
@snapend

