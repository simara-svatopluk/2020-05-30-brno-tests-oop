# Testy ke kvalitnímu OOP

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
Více stejných produktů
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
Smazání produktu
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
Mazání neexistující produktu
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

@snap[center span-50 text-center text-black text-left]
Vnější pohled na objekt
```php
$cart->read()
```

@css[text-white](Ujasnit si očekávání)
@css[text-white](Ujasnit si očekávání)
@snapend

---

@snap[center span-50 text-center text-black text-left]
Vnější pohled na objekt
```php
$cart->read()
```

Ujasnit si očekávání
```php
$expected = [...]
```
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

