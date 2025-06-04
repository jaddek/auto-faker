<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class TitleFaker extends NameFaker
{
    /**
     * @var array<string>
     */
    private array $data = [
        "To Kill a Mockingbird", "1984", "Pride and Prejudice", "The Great Gatsby",
        "Moby-Dick", "War and Peace", "The Catcher in the Rye", "The Hobbit",
        "The Lord of the Rings", "Brave New World", "Jane Eyre", "Wuthering Heights",
        "The Chronicles of Narnia", "Frankenstein", "Dracula", "Animal Farm",
        "Crime and Punishment", "The Brothers Karamazov", "Great Expectations", "Little Women",
        "The Odyssey", "The Iliad", "Les Misérables", "Don Quixote", "The Divine Comedy",
        "Fahrenheit 451", "The Picture of Dorian Gray", "The Alchemist", "The Stranger", "The Count of Monte Cristo",
        "One Hundred Years of Solitude", "Love in the Time of Cholera", "A Tale of Two Cities",
        "The Kite Runner", "Memoirs of a Geisha",
        "The Book Thief", "Life of Pi", "The Shadow of the Wind", "A Man Called Ove", "The Midnight Library",
        "Where the Crawdads Sing", "Normal People", "Educated", "Sapiens", "Becoming",
        "Atomic Habits", "Thinking, Fast and Slow", "Outliers", "Quiet", "The Power of Now",
        "Man’s Search for Meaning", "The Subtle Art of Not Giving a F*ck", "12 Rules for Life",
        "Deep Work", "Digital Minimalism",
        "Can’t Hurt Me", "Dune", "Ender’s Game", "The Martian", "Project Hail Mary",
        "Neuromancer", "Snow Crash", "Ready Player One", "The Left Hand of Darkness", "The Three-Body Problem",
        "Red Mars", "Hyperion", "Foundation", "I, Robot", "Do Androids Dream of Electric Sheep?",
        "Good Omens", "American Gods", "Neverwhere", "Coraline", "Stardust",
        "The Name of the Wind", "The Wise Man’s Fear", "Mistborn", "The Final Empire", "The Way of Kings",
        "Elantris", "The Blade Itself", "Before They Are Hanged", "Best Served Cold", "The Lies of Locke Lamora",
        "Six of Crows", "Crooked Kingdom", "Shadow and Bone", "Throne of Glass", "A Court of Thorns and Roses",
        "The Hunger Games", "Catching Fire", "Mockingjay", "Divergent", "Insurgent",
        "Allegiant", "The Maze Runner", "Scorch Trials", "Death Cure", "The Giver"
    ];

    #[\Override]
    public function __invoke(): string
    {
        return array_rand(array_flip($this->data));
    }
}
