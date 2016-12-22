#!/usr/bin/perl

use Lingua::Identify qw(:language_identification);
use JSON;
use warnings;
use strict;
binmode STDOUT, ":utf8";
binmode STDIN, ":utf8";
use utf8;

chomp(my $filename = <STDIN>);

# Lets open the given file:
open(my $fh, "<", $filename)
	or die "Can't open < $filename: $!";
my $json = <$fh>;
close $fh;

# Decode the JSON String
my $data = JSON->new->utf8->decode($json);

# Wir durchlaufen den Hash:
foreach my $key (keys %{$data}){
	$data->{$key} = langof($data->{$key});
}

$data = encode_json($data);

# Nur noch die temporäre Datei löschen:
unlink($filename);

print $data;
