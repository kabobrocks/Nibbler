
--
-- Database: `nibbler`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL COMMENT 'post id#',
  `userid` int(10) NOT NULL,
  `post` varchar(140) NOT NULL,
  `stamp` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

-- Populate 3 nibbles to begin

INSERT INTO `posts` (`id`, `userid`, `post`, `stamp`) VALUES
(1, 1, 'Hello this is a Nibble', 1519172309),
(2, 2, 'I am also posting a Nibble', 1519189181),
(3, 1, 'This is another Nibble', 1519189269);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(10) NOT NULL,
  `handle` varchar(25) NOT NULL,
  `imageURL` varchar(200) NOT NULL,
  `created` int(10) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

-- Insert default user,   password is  "password"

INSERT INTO `users` (`userid`, `handle`, `imageURL`, `created`, `password`) VALUES
(1, 'ModernSonic', 'https://i.imgur.com/bJv8Akq.jpg', 1499351148, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'),
(2, 'ManiaSonic',  'https://i.imgur.com/PY8RTBz.jpg', 1499351148, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'post id#',AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;